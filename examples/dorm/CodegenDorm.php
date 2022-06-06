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
 * that will allow to read the data from a database and store it
 * in the object.
 */
final class CodegenDorm {

  private ICodegenFactory $codegen;

  public function __construct(
    private DormSchema $schema,
  ) {
    $this->codegen = new HackCodegenFactory(
      (new HackCodegenConfig())->withRootDir(__DIR__.'/../..'),
    );
  }

  private function getSchemaName(): string {
    $ref = new \ReflectionClass($this->schema);
    $name = $ref->getShortName();
    return Str\strip_suffix($name, 'Schema');
  }

  public function generate(): void {
    $cg = $this->codegen;

    // Here's an example of how to generate the code for a class.
    // Notice the fluent interface.  It's possible to generate
    // everything in the same method, however, for clarity
    // sometimes it's easier to use helper methods such as
    // getConstructor or getLoad in this examples.
    $class = $cg->codegenClass($this->getSchemaName())
      ->setIsFinal()
      ->setConstructor($this->getConstructor())
      ->addMethod($this->getLoad())
      ->addMethods($this->getGetters())
      ->addTypeConstant(
        $cg->codegenTypeConstant('TData')
          ->setValue(
            $this->getDatabaseRowShape(),
            HackBuilderValues::codegen(),
          )
      );

    $rc = new \ReflectionClass(\get_class($this->schema));
    $path = $rc->getFileName() as string;
    $pos = \strrpos((string) $path, '/');
    $dir = \substr($path, 0, $pos + 1);
    $gen_from = 'codegen.hack '.$this->getSchemaName().'Schema';

    // This generates a file (we pass the file name) that contains the
    // class defined above and saves it.
    // Using setGeneratedFrom adds in the clas docblock a reference
    // to the script that is used to generate the file.
    // Notice that saving the file includes also verifying the checksum
    // of the existing file and merging it if it's partially generated.
    $cg->codegenFile($dir.$this->getSchemaName().'.php')
      ->useNamespace('Facebook\\TypeAssert')
      ->addClass($class)
      ->setGeneratedFrom($cg->codegenGeneratedFromScript($gen_from))
      ->save();
  }

  private function getConstructor(): CodegenConstructor {
    // Example of how to generate a constructor.  Very similar
    // to generating a method, but using $cg->codegenConstructor()
    // doesn't require to set the name since it's always __constructor
    return $this->codegen->codegenConstructor()
      ->setPrivate()
      ->addParameter('private self::TData $data');
  }

  private function getLoad(): CodegenMethod {
    $sql = 'select * from '.
      $this->schema->getTableName().
      ' where '.$this->schema->getIdField().'=\'.$id.\'';

    // Here's how to build a block of code using hack_builder.
    // Notice that some methods have a sprintf style of arguments
    // to make it easier to build expressions.
    // There are specific methods that make easier to write "if",
    // "foreach", etc.  See HackBuilder documentation.
    $body = $this->codegen->codegenHackBuilder()
      ->addLinef('$conn = new PDO(\'%s\');', $this->schema->getDsn())
      ->add('$cursor = ')
      ->addMultilineCall('$conn->query', Vector {"'".$sql."'"}, true)
      ->addLine('$result = $cursor->fetch(PDO::FETCH_ASSOC);')
      ->startIfBlock('!$result')
      ->addReturnf('null')
      ->endIfBlock()
      ->addAssignment(
        '$ts',
        "type_structure(self::class, 'TData')",
        HackBuilderValues::literal(),
      )
      ->addAssignment(
        '$data',
        'TypeAssert\\matches_type_structure($ts, $result)',
        HackBuilderValues::literal(),
      )
      ->addReturnf('new %s($data)', $this->getSchemaName());

    // Here's an example of how to generate a method.  It's common when
    // the code in the method is not trivial to build it using hack_builder.
    // Notice how the parameter and the return type are set.
    return $this->codegen->codegenMethod('load')
      ->setIsStatic()
      ->addParameter('int $id')
      ->setReturnType('?'.$this->getSchemaName())
      ->setBody($body->getCode());
  }

  private function getGetters(): Vector<CodegenMethod> {
    $cg = $this->codegen;
    $methods = Vector {};
    foreach ($this->schema->getFields() as $name => $field) {
      $return_type = $field->getType();
      $data = '$this->data[\''.$field->getDbColumn().'\']';
      if ($return_type === \DateTime::class) {
        $return_data = '(new DateTime())->setTimestamp($value)';
      } else {
        $return_data = '$value';
      }
      if ($field->isOptional()) {
        $return_type = '?'.$return_type;
        $builder = $cg->codegenHackBuilder();
        if ($field->isManual()) {
          // This part illustrates how to include a manual section, which the
          // user can edit and it will be kept even if the code is regenerated.
          // Notice that each section needs to have a unique name, since that's
          // used to match the section when re-generating the code
          $builder
            ->startManualSection($name)
            ->addInlineComment('You may manually change this section of code');
        }
        // using addWithSuggestedLineBreaks will allow the code
        // to break automatically on long lines on the specified places.
        $builder
          ->addAssignment(
            '$value',
            $data.' ?? null',
            HackBuilderValues::literal(),
          )
          ->addWithSuggestedLineBreaksf(
            "return %s === null\0? null\0: %s;",
            '$value',
            $return_data,
          );
        if ($field->isManual()) {
          // You always need to close a manual section
          $builder->endManualSection();
        }
        $body = $builder->getCode();
      } else {
        $body =
          $cg->codegenHackBuilder()
            ->addAssignment(
              '$value',
              $data,
              HackBuilderValues::literal(),
            )
            ->addReturn($return_data, HackBuilderValues::literal())
            ->getCode();
      }
      $methods[] = $cg->codegenMethod('get'.$name)
        ->setReturnType($return_type)
        ->setBody($body);
    }
    return $methods;
  }

  private function getDatabaseRowShape(): CodegenShape {
    $db_fields = vec[];
    foreach ($this->schema->getFields() as $field) {
      $type = $field->getType();
      if ($type === \DateTime::class) {
        $type = 'int';
      }
      $type = $field->isOptional() ? '?'.$type : $type;

      $field = new CodegenShapeMember($field->getDbColumn(), $type);
      $db_fields[] = $field;
    }
    return $this->codegen->codegenShape(...$db_fields);
  }
}
