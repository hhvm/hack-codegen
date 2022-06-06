<?hh // strict
/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use namespace HH\Lib\Str;

/**
 * For a given DormSchema, this class generates code for a class
 * that will allow to insert rows in a database.
 */
final class CodegenMutator {

  private HackCodegenFactory $codegen;

  public function __construct(
    private DormSchema $schema,
  ) {
    $this->codegen = new HackCodegenFactory(
      (new HackCodegenConfig())->withRootDir(__DIR__.'/../..'),
    );
  }

  private function getName(): string {
    $ref = new \ReflectionClass($this->schema);
    $name = $ref->getShortName();
    $remove_schema = Str\strip_suffix($name, 'Schema');
    return $remove_schema.'Mutator';
  }

  public function generate(): void {
    $cg = $this->codegen;
    $name = $this->getName();
    // Here's an example of how to generate the code for a class.
    // Notice the fluent interface.  It's possible to generate
    // everything in the same method, however, for clarity
    // sometimes it's easier to use helper methods such as
    // getConstructor or getLoad in this examples.
    $class = $cg->codegenClass($name)
      ->setIsFinal()
      ->addProperty($this->getDataVar())
      ->addProperty($this->getPdoTypeVar())
      ->setConstructor($this->getConstructor())
      ->addMethod($this->getCreateMethod())
      ->addMethod($this->getUpdateMethod())
      ->addMethod($this->getSaveMethod())
      ->addMethod($this->getCheckRequiredFieldsMethod())
      ->addMethods($this->getSetters());

    $rc = new \ReflectionClass(\get_class($this->schema));
    $path = $rc->getFileName() as string;
    $pos = \strrpos($path, '/');
    $dir = \substr($path, 0, $pos + 1);
    $gen_from = 'codegen.php '.$rc->getShortName();

    // This generates a file (we pass the file name) that contains the
    // class defined above and saves it.
    $cg->codegenFile($dir.$name.'.php')
      ->addClass($class)
      ->setGeneratedFrom($cg->codegenGeneratedFromScript($gen_from))
      ->save();
  }

  private function getDataVar(): CodegenProperty{
    // Example of how to generate a class member variable, including
    // setting an initial value.
    return $this->codegen->codegenProperty('data')
      ->setType('Map<string, mixed>')
      ->setValue(Map {}, HackBuilderValues::export());
  }

  private function getPdoTypeVar(): CodegenProperty {
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

    $cg = $this->codegen;
    return $cg->codegenProperty('pdoType')
      ->setType('Map<string, int>')
      ->setIsStatic()
      ->setValue(
        $values,
        HackBuilderValues::map(
          HackBuilderKeys::export(),
          HackBuilderValues::literal(),
        ),
      );
  }

  private function getConstructor(): CodegenConstructor {
    // This very simple exampe of generating a constructor shows
    // how to change its accesibility to private.  The same would
    // work in a method.
    return $this->codegen->codegenConstructor()
      ->addParameter('private ?int $id = null')
      ->setPrivate();
  }

  private function getCreateMethod(): CodegenMethod {
    $cg = $this->codegen;
    // This is a very simple example of generating a method
    return $cg->codegenMethod('create')
      ->setIsStatic(true)
      ->setReturnType('this')
      ->setBody(
        $cg->codegenHackBuilder()
        ->addReturnf('new %s()', $this->getName())
        ->getCode()
      );
  }

  private function getUpdateMethod(): CodegenMethod {
    $cg = $this->codegen;
    // This is a very simple example of generating a method
    return $cg->codegenMethod('update')
      ->setIsStatic(true)
      ->addParameter('int $id')
      ->setReturnType('this')
      ->setBody(
        $cg->codegenHackBuilder()
        ->addReturnf('new %s($id)', $this->getName())
        ->getCode()
      );
  }

  private function getSaveMethod(): CodegenMethod {
    $cg = $this->codegen;
    // Here's an example of building a piece of code with hack_builder.
    // Notice addMultilineCall, which makes easy to call a method and
    // wrap it in multiple lines if needed to.
    // Also notice that you can use startIfBlock to write an if statement
    $body = $cg->codegenHackBuilder()
      ->addLinef('$conn = new PDO(\'%s\');', $this->schema->getDsn())
      ->addMultilineCall(
        '$quoted = $this->data->mapWithKey',
        Vector{'($k, $v) ==> $conn->quote((string) $v, self::$pdoType[$k])'},
      )
      ->addAssignment(
        '$id',
        '$this->id',
        HackBuilderValues::literal(),
      )
      ->startIfBlock('$id === null')
      ->addLine('$this->checkRequiredFields();')
      ->addLine('$names = \'(\'.implode(\',\', $quoted->keys()).\')\';')
      ->addLine('$values = \'(\'.implode(\',\', $quoted->values()).\')\';')
      ->addLinef(
        '$st = \'insert into %s \'.$names.\' values \'.$values;',
        $this->schema->getTableName(),
      )
      ->addLine('$conn->exec($st);')
      ->addReturnf('(int) $conn->lastInsertId()')
      ->addElseBlock()
      ->addAssignment(
        '$pairs',
        '$quoted->mapWithKey(($field, $value) ==>  $field.\'=\'.$value)',
        HackBuilderValues::literal(),
      )
      ->addLinef(
        '$st = \'update %s set \'.implode(\',\', $pairs).\' where %s=\'.$id;',
        $this->schema->getTableName(),
        $this->schema->getIdField(),
      )
      ->addLine('$conn->exec($st);')
      ->addReturnf('$id')
      ->endIfBlock();

    return $cg->codegenMethod('save')
      ->setReturnType('int')
      ->setBody($body->getCode());
  }

  private function getCheckRequiredFieldsMethod(): CodegenMethod {
    $required = $this->schema->getFields()
      ->filter($field ==> !$field->isOptional())
      ->map($field ==> $field->getDbColumn())
      ->values()->toSet();

    $cg = $this->codegen;

    $body = $cg->codegenHackBuilder()
      ->add('$required = ')
      ->addValue(
        $required,
        HackBuilderValues::set(
          HackBuilderValues::export(),
        ),
      )
      ->closeStatement()
      ->addAssignment(
        '$missing',
        '$required->removeAll($this->data->keys())',
        HackBuilderValues::literal(),
      )
      ->addMultilineCall(
        'invariant',
        Vector {
          '$missing->isEmpty()',
          '\'The following required fields are missing: %s\'',
          'implode(\', \', $missing)',
        }
      );

    return $cg->codegenMethod('checkRequiredFields')
      ->setReturnType('void')
      ->setBody($body->getCode());
  }

  private function getSetters(): Vector<CodegenMethod> {
    $cg = $this->codegen;
    $methods = Vector {};
    foreach($this->schema->getFields() as $name => $field) {
      if ($field->getType() === 'DateTime') {
        $value = '$value->format(\'Y-m-d\')';
      } else {
        $value = '$value';
      }

      $body = $cg->codegenHackBuilder();
      if ($field->isManual()) {
        // This part illustrates how to include a manual section, which the
        // user can edit and it will be kept even if the code is regenerated.
        // Notice that each section needs to have a unique name, since that's
        // used to match the section when re-generating the code
        $body
          ->startManualSection($name)
          ->addInlineComment('You may manually change this section of code');
      }
      $body
        ->addLinef('$this->data[\'%s\'] = %s;', $field->getDbColumn(), $value);

      if ($field->isManual()) {
        // You always need to close a manual section
        $body->endManualSection();
      }

      $body->addReturnf('$this');

      $methods[] = $cg->codegenMethod('set'.$name)
        ->setReturnType('this')
        ->addParameter($field->getType().' $value')
        ->setBody($body->getCode());
    }
    return $methods;
  }
}
