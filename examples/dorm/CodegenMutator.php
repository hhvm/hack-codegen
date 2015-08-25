<?hh // strict
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

namespace Facebook\HackCodegen;

/**
 * For a given DormSchema, this class generates code for a class
 * that will allow to insert rows in a database.
 */
class CodegenMutator {

  public function __construct(
    private DormSchema $schema,
  ) {}

  private function getName(): string {
    $ref = new \ReflectionClass($this->schema);
    $name = $ref->getShortName();
    $remove_schema = Str::endsWith($name, 'Schema')
      ? Str::substr($name, 0, -6)
      : $name;
    return $remove_schema.'Mutator';
  }

  public function generate(): void {
    $name = $this->getName();
    // Here's an example of how to generate the code for a class.
    // Notice the fluent interface.  It's possible to generate
    // everything in the same method, however, for clarity
    // sometimes it's easier to use helper methods such as
    // getConstructor or getLoad in this examples.
    $class = codegen_class($name)
      ->setIsFinal()
      ->addVar($this->getDataVar())
      ->addVar($this->getPdoTypeVar())
      ->setConstructor($this->getConstructor())
      ->addMethod($this->getCreateMethod())
      ->addMethod($this->getUpdateMethod())
      ->addMethod($this->getSaveMethod())
      ->addMethod($this->getCheckRequiredFieldsMethod())
      ->addMethods($this->getSetters());

    $rc = new \ReflectionClass(get_class($this->schema));
    $path = $rc->getFileName();
    $pos = strrpos($path, '/');
    $dir = substr($path, 0, $pos + 1);

    // This generates a file (we pass the file name) that contains the
    // class defined above and saves it.
    codegen_file($dir.$name.'.php')
      ->addClass($class)
      ->setIsStrict(true)
      ->setGeneratedFrom(codegen_generated_from_script())
      ->save();
  }


  private function getDataVar(): CodegenMemberVar {
    // Example of how to generate a class member variable, including
    // setting an initial value.
    return codegen_member_var('data')
      ->setType('Map<string, mixed>')
      ->setValue(Map {});
  }

  private function getPdoTypeVar(): CodegenMemberVar {
    $values = Map {};
    foreach ($this->schema->getFields() as $field) {
      switch($field->getType()) {
        case 'string':
        case 'DateTime':
          $type = 'PDO::PARAM_STR';
          break;
        case 'int':
          $type = 'PDO::PARAM_INT';
          break;
        case 'bool':
          $type = 'PDO::PARAM_BOOL';
          break;
        default:
          invariant_violation('Undefined PDO type for %s', $field->getType());
      }
      $values[$field->getDbColumn()] = $type;
    }

    // Here's how we add the code for a Map. In hack_builder, the methods for
    // adding collections allow to customize the rendering for keys/values
    // to be either EXPORT or LITERAL.  By default is EXPORT, which will cause,
    // for example, that if you pass a string, quotes will be added.
    // LITERAL will just output the value without processing.  Since the values
    // are, for example PDO::PARAM_STR, if we use EXPORT it would be
    // 'PDO::PARAM_STR', but using LITERAL it's PDO::PARAM_STR
    $code = hack_builder()
      ->addMap($values, HackBuilderKeys::EXPORT, HackBuilderValues::LITERAL);

    return codegen_member_var('pdoType')
      ->setType('Map<string, int>')
      ->setIsStatic()
      ->setLiteralValue($code->getCode());
  }

  private function getConstructor(): CodegenConstructor {
    // This very simple exampe of generating a constructor shows
    // how to change its accesibility to private.  The same would
    // work in a method.
    return codegen_constructor()
      ->addParameter('private ?int $id = null')
      ->setPrivate();
  }

  private function getCreateMethod(): CodegenMethod {
    // This is a very simple example of generating a method
    return codegen_method('create')
      ->setReturnType('this')
      ->setBody(
        hack_builder()
        ->addReturn('new %s()', $this->getName())
        ->getCode()
      );
  }

  private function getUpdateMethod(): CodegenMethod {
    // This is a very simple example of generating a method
    return codegen_method('update')
      ->addParameter('int $id')
      ->setReturnType('this')
      ->setBody(
        hack_builder()
        ->addReturn('new %s($id)', $this->getName())
        ->getCode()
      );
  }

  private function getSaveMethod(): CodegenMethod {
    // Here's an example of building a piece of code with hack_builder.
    // Notice addMultilineCall, which makes easy to call a method and
    // wrap it in multiple lines if needed to.
    // Also notice that you can use startIfBlock to write an if statement
    $body = hack_builder()
      ->addLine('$conn = new PDO(\'%s\');', $this->schema->getDsn())
      ->addMultilineCall(
        '$quoted = $this->data->mapWithKey',
        Vector{'($k, $v) ==> $conn->quote($v, self::$pdoType[$k])'},
      )
      ->addAssignment('$id', '$this->id')
      ->startIfBlock('$id === null')
      ->addLine('$this->checkRequiredFields();')
      ->addLine('$names = "(".implode(",", $quoted->keys()).")";')
      ->addLine('$values = "(".implode(",", $quoted->values()).")";')
      ->addLine(
        '$st = "insert into %s $names values $values";',
        $this->schema->getTableName(),
      )
      ->addLine('$conn->exec($st);')
      ->addReturn('(int) $conn->lastInsertId()')
      ->addElseBlock()
      ->addAssignment(
        '$pairs',
        '$quoted->mapWithKey(($field, $value) ==>  "$field=$value")',
      )
      ->addLine(
        '$st = "update %s set ".implode(",", $pairs)." where %s=".$this->id;',
        $this->schema->getTableName(),
        $this->schema->getIdField(),
      )
      ->addLine('$conn->exec($st);')
      ->addReturn('$id')
      ->endIfBlock();

    return codegen_method('save')
      ->setReturnType('int')
      ->setBody($body->getCode());
  }

  private function getCheckRequiredFieldsMethod(): CodegenMethod {
    $required = $this->schema->getFields()
      ->filter($field ==> !$field->isOptional())
      ->map($field ==> $field->getDbColumn())
      ->values()->toSet();

    $body = hack_builder()
      ->add('$required = ')
      ->addSet($required)
      ->closeStatement()
      ->addAssignment(
        '$missing',
        '$required->removeAll($this->data->keys());'
      )
      ->addMultilineCall(
        'invariant',
        Vector {
          '$missing->isEmpty()',
          '"The following required fields are missing: "'.
            '.implode(", ", $missing)',
        }
      );

    return codegen_method('checkRequiredFields')
      ->setReturnType('void')
      ->setBody($body->getCode());
  }

  private function getSetters(): Vector<CodegenMethod> {
    $methods = Vector {};
    foreach($this->schema->getFields() as $name => $field) {
      if ($field->getType() === 'DateTime') {
        $value = '$value->format("Y-m-d")';
      } else {
        $value = '$value';
      }

      $body = hack_builder();
      if ($field->isManual()) {
        // This part illustrates how to include a manual section, which the
        // user can edit and it will be kept even if the code is regenerated.
        // Notice that each section needs to have a unique name, since that's
        // used to match the section when re-generating the code
        $body
          ->beginManualSection($name)
          ->addInlineComment('You may manually change this section of code');
      }
      $body
        ->addLine('$this->data["%s"] = %s;', $field->getDbColumn(), $value);

      if ($field->isManual()) {
        // You always need to close a manual section
        $body->endManualSection();
      }

      $body->addReturn('$this');

      $methods[] = codegen_method('set'.$name)
        ->setReturnType('this')
        ->addParameter($field->getType().' $value')
        ->setBody($body->getCode());
    }
    return $methods;
  }
}
