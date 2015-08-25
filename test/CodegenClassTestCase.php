<?hh
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

namespace Facebook\HackCodegen;

final class CodegenClassTestCase extends CodegenBaseTest {

  public function testDocblock() {
    $code = codegen_class('TestDocblock')
      ->setDocBlock(
        "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed ".
        "do eiusmod tempor incididunt ut labore et dolore magna aliqua. ".
        "Ut enim ad minim veniam, quis nostrud exercitation ullamco ".
        "laboris nisi ut aliquip ex ea commodo consequat.\n".
        "Understood?\n".
        "Yes!"
      )
      ->render();

    self::assertUnchanged($code);
  }

  public function testExtendsAndFinal() {
    $code = codegen_class('NothingHere')
      ->setExtends('NothingHereBase')
      ->addInterface(codegen_implements_interface('JustOneInterface'))
      ->setIsFinal()
      ->render();

    self::assertUnchanged($code);
  }

  public function testInterfacesAndAbstract() {
    $code = codegen_class('NothingHere')
      ->addInterface(codegen_implements_interface('INothing'))
      ->addInterface(
        codegen_implements_interface('IMeh')
        ->setGeneratedFrom(codegen_generated_from_method("Foo", "Bar"))
      )
      ->setIsAbstract()
      ->render();

    self::assertUnchanged($code);
  }

  public function testMultipleInterfaces() {
    $interfaces = Vector {
      'IHarryPotter',
      'IHermioneGranger',
      'IRonWeasley',
    };

    $code = codegen_class('JKRowling')
      ->addInterfaces(codegen_implements_interfaces($interfaces))
      ->render();

    self::assertUnchanged($code);
  }

  public function testLongClassDeclaration() {
    // The class declaration is just long enough (82 chars) to make it wrap
    $code = codegen_class('ClassWithReallyLongName')
      ->setExtends('NowThisIsTheParentClassWithALongNameItSelf')
      ->render();

    self::assertUnchanged($code);
  }

  public function testLongClassDeclarationWithInterfaces() {
    $interfaces = Vector {
      'InterfaceUno',
      'InterfaceDos',
      'InterfaceTres',
    };
    $code = codegen_class('ClassWithReallyReallyLongName')
      ->setExtends('NowThisIsTheParentClassWithALongNameItSelf')
      ->addInterfaces(codegen_implements_interfaces($interfaces))
      ->render();

    self::assertUnchanged($code);
  }

  public function testClassDeclarationWithGenerics() {
    $generics_decl = Map {
      'Tent' => IEnt::class,
      'T' => "",
      'Tstory' => "EntCreationStory<Tent>",
    };

    $code = codegen_class('ClassWithGenerics')
      ->setGenericsDecl($generics_decl)
      ->render();

    self::assertUnchanged($code);
  }

  public function testDemo() {
    $code = codegen_class('Demo')
      ->addTrait(codegen_uses_trait('EntProvisionalMode'))
      ->addTrait(
        codegen_uses_trait('WhateverTrait')
        ->setGeneratedFrom(codegen_generated_from_method("Whatever", "Method"))
      )
      ->addTrait(codegen_uses_trait("Useless"))
      ->addConst('MAX_SIZE', 256)
      ->addConst('DEFAULT_NAME', 'MyEnt', 'Default name of Ent.')
      ->addConst('PI', 3.1415)
      ->setHasManualMethodSection()
      ->setHasManualDeclarations()
      ->addVar(
        codegen_member_var('text')->setProtected()->setType('string')
      )
      ->addVar(
        codegen_member_var('id')->setType('?int')->setValue(12345)
      )
      ->setConstructor(
        codegen_constructor()
          ->addParameter('string $text')
          ->setBody('$this->text = $text;')
      )
      ->addMethod(
        codegen_method('getText')
          ->setIsFinal()
          ->setReturnType('string')
          ->setBody('return $this->text;')
      )
      ->addMethod(
        codegen_method('genX')
          ->setProtected()
          ->setDocBlock(
            'This is a 76 characters  comment to test the splitting '.
            'based on indentation.',
          )
          ->setReturnType('Awaitable<int>')
          ->setManualBody()
          ->setBody('// your code here')
      )
      ->setIsFinal()
      ->render();

    self::assertUnchanged($code);
  }

  public function testLongGeneratedFrom() {
    $code = codegen_class('Demo')
      ->addMethod(
        codegen_method('getRawIntEnumCustomTest')
        ->setGeneratedFrom(
          codegen_generated_from_method_with_key(
            'EntTestFieldGettersCodegenSchema',
            'getFieldSpecification',
            'RawIntEnumCustomTest',
          )
        )
      )->render();

    self::assertUnchanged($code);
  }

  public function testConstructorWrapperFuncDefault() {
    $code = codegen_class('TestWrapperFunc')
      ->setDocBlock(
        "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed ".
        "do eiusmod tempor incididunt ut labore et dolore magna aliqua. ".
        "Ut enim ad minim veniam, quis nostrud exercitation ullamco ".
        "laboris nisi ut aliquip ex ea commodo consequat.\n".
        "Understood?\n".
        "Yes!"
      )
      ->addConstructorWrapperFunc()
      ->render();

    self::assertUnchanged($code);
  }

  public function testConstructorWrapperFunc() {
    $code = codegen_class('TestWrapperFunc')
      ->addVar(
        codegen_member_var('text')->setPrivate()->setType('string')
      )
      ->addVar(
        codegen_member_var('hack')->setType('?bool')->setValue(false)
      )
      ->setConstructor(
        codegen_constructor()
          ->addParameter('string $text, ?bool $hack')
          ->setBody('$this->text = $text;')
      )
      ->addConstructorWrapperFunc()
      ->render();

    self::assertUnchanged($code);
  }

  /*
   * When current class has no constructor specified but its parent class does,
   * we need to specify the parameters of the wrapper function explictly
   *   e.g. parent class StrangeParent has the following constructor
   *        function __construct(string $text) {
   *          // whatever
   *        }
   */
  public function testConstructorWrapperFuncWithExplicitParams() {
    $code = codegen_class('TestWrapperFunc')
      ->setExtends('StrangeParent')
      ->addConstructorWrapperFunc(Vector {'string $text'})
      ->render();

    self::assertUnchanged($code);
  }

}
