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
 * that will allow to read the data from a database and store it
 * in the object.
 */
class CodegenDorm {

  public function __construct(
    private DormSchema $schema,
  ) {}

  private function getSchemaName(): string {
    $ref = new \ReflectionClass($this->schema);
    $name = $ref->getShortName();
    return Str::endsWith($name, 'Schema')
      ? Str::substr($name, 0, -6)
      : $name;
  }

  public function generate(): void {
    // Here's an example of how to generate the code for a class.
    // Notice the fluent interface.  It's possible to generate
    // everything in the same method, however, for clarity
    // sometimes it's easier to use helper methods such as
    // getConstructor or getLoad in this examples.
    $class = codegen_class($this->getSchemaName())
      ->setIsFinal()
      ->setConstructor($this->getConstructor())
      ->addMethod($this->getLoad())
      ->addMethods($this->getGetters());

    $rc = new \ReflectionClass(get_class($this->schema));
    $path = $rc->getFileName();
    $pos = strrpos($path, '/');
    $dir = substr($path, 0, $pos + 1);

    // This generates a file (we pass the file name) that contains the
    // class defined above and saves it.
    // Using setGeneratedFrom adds in the clas docblock a reference
    // to the script that is used to generate the file.
    // Notice that saving the file includes also verifying the checksum
    // of the existing file and merging it if it's partially generated.
    codegen_file($dir.$this->getSchemaName().'.php')
      ->addClass($class)
      ->setGeneratedFrom(codegen_generated_from_script())
      ->save();
  }

  private function getConstructor(): CodegenConstructor {
    // Example of how to generate a constructor.  Very similar
    // to generating a method, but using codegen_constructor()
    // doesn't require to set the name since it's always __constructor
    return codegen_constructor()
      ->setPrivate()
      ->addParameter('private Map<string, mixed> $data');
  }

  private function getLoad(): CodegenMethod {
    $sql = 'select * from '.
      $this->schema->getTableName().
      ' where '.$this->schema->getIdField().'=$id';

    // Here's how to build a block of code using hack_builder.
    // Notice that some methods have a sprintf style of arguments
    // to make it easier to build expressions.
    // There are specific methods that make easier to write "if",
    // "foreach", etc.  See HackBuilder documentation.
    $body = hack_builder()
      ->addLine('$conn = new PDO(\'%s\');', $this->schema->getDsn())
      ->add('$cursor = ')
      ->addMultilineCall('$conn->query', Vector {"\"$sql\""}, true)
      ->addLine('$result = $cursor->fetch(PDO::FETCH_ASSOC);')
      ->startIfBlock('!$result')
      ->addReturn('null')
      ->endIfBlock()
      ->addReturn('new %s(new Map($result))', $this->getSchemaName());

    // Here's an example of how to generate a method.  It's common when
    // the code in the method is not trivial to build it using hack_builder.
    // Notice how the parameter and the return type are set.
    return codegen_method('load')
      ->setIsStatic()
      ->addParameter('int $id')
      ->setReturnType('?'.$this->getSchemaName())
      ->setBody($body->getCode());
  }

  private function getGetters(): Vector<CodegenMethod> {
    $methods = Vector {};
    foreach ($this->schema->getFields() as $name => $field) {
      $return_type = $field->getType();
      $data = '$this->data[\''.$field->getDbColumn().'\']';
      $return_data = $data;
      if ($return_type == 'DateTime') {
        $return_data = 'new DateTime('.$data.')';
      } else {
        $return_data = "($return_type) $data";
      }
      if ($field->isOptional()) {
        $return_type = '?'.$return_type;
        $builder = hack_builder();
        if ($field->isManual()) {
          // This part illustrates how to include a manual section, which the
          // user can edit and it will be kept even if the code is regenerated.
          // Notice that each section needs to have a unique name, since that's
          // used to match the section when re-generating the code
          $builder
            ->beginManualSection($name)
            ->addInlineComment('You may manually change this section of code');
        }
        // using addWithSuggestedLineBreaks will allow the code
        // to break automatically on long lines on the specified places.
        $builder->addWithSuggestedLineBreaks(
          "return isset($data)\t? $return_data\t: null;",
        );
        if ($field->isManual()) {
          // You always need to close a manual section
          $builder->endManualSection();
        }
        $body = $builder->getCode();
      } else {
        $body = 'return '.$return_data.';';
      }
      $methods[] = codegen_method('get'.$name)
        ->setReturnType($return_type)
        ->setBody($body);
    }
    return $methods;
  }
}
