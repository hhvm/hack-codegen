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

final class CodegenClassTest extends CodegenBaseTest {

  public function testDocblock(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenClass('TestDocblock')
      ->setDocBlock(
        "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed ".
        "do eiusmod tempor incididunt ut labore et dolore magna aliqua. ".
        "Ut enim ad minim veniam, quis nostrud exercitation ullamco ".
        "laboris nisi ut aliquip ex ea commodo consequat.\n".
        "Understood?\n".
        "Yes!",
      )
      ->render();

    $this->assertUnchanged($code);
  }

  public function testExtendsAndFinal(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenClass('NothingHere')
      ->setExtends('NothingHereBase')
      ->addInterface($cgf->codegenImplementsInterface('JustOneInterface'))
      ->setIsFinal()
      ->render();

    $this->assertUnchanged($code);
  }

  public function testInterfacesAndAbstract(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenClass('NothingHere')
      ->addInterface($cgf->codegenImplementsInterface('INothing'))
      ->addInterface(
        $cgf
          ->codegenImplementsInterface('IMeh')
          ->setGeneratedFrom($cgf->codegenGeneratedFromMethod("Foo", "Bar")),
      )
      ->setIsAbstract()
      ->render();

    $this->assertUnchanged($code);
  }

  public function testMultipleInterfaces(): void {
    $interfaces = Vector { 'IHarryPotter', 'IHermioneGranger', 'IRonWeasley' };

    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenClass('JKRowling')
      ->addInterfaces($cgf->codegenImplementsInterfaces($interfaces))
      ->render();

    $this->assertUnchanged($code);
  }

  public function testLongClassDeclaration(): void {
    // The class declaration is just long enough (82 chars) to make it wrap
    $code = $this
      ->getCodegenFactory()
      ->codegenClass('ClassWithReallyLongName')
      ->setExtends('NowThisIsTheParentClassWithALongNameItSelf')
      ->render();

    $this->assertUnchanged($code);
  }

  public function testLongClassDeclarationWithInterfaces(): void {
    $interfaces = Vector { 'InterfaceUno', 'InterfaceDos', 'InterfaceTres' };
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenClass('ClassWithReallyReallyLongName')
      ->setExtends('NowThisIsTheParentClassWithALongNameItSelf')
      ->addInterfaces($cgf->codegenImplementsInterfaces($interfaces))
      ->render();

    $this->assertUnchanged($code);
  }

  public function testClassDeclarationWithGenerics(): void {
    $generics_decl =
      vec['Tent as Ixyz', 'T', 'Tstory as EntCreationStory<Tent>'];

    $code = $this
      ->getCodegenFactory()
      ->codegenClass('ClassWithGenerics')
      ->addGenerics($generics_decl)
      ->render();

    $this->assertUnchanged($code);
  }

  public function testDemo(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenClass('Demo')
      ->addTrait($cgf->codegenUsesTrait('EntProvisionalMode'))
      ->addTrait(
        $cgf
          ->codegenUsesTrait('WhateverTrait')
          ->setGeneratedFrom(
            $cgf->codegenGeneratedFromMethod("Whatever", "Method"),
          ),
      )
      ->addTrait($cgf->codegenUsesTrait("Useless"))
      ->addConst('MAX_SIZE', 256)
      ->addConst('DEFAULT_NAME', 'MyEnt', 'Default name of Ent.')
      ->addConst('PI', 3.1415)
      ->setHasManualMethodSection()
      ->setHasManualDeclarations()
      ->addProperty(
        $cgf->codegenProperty('text')->setProtected()->setType('string'),
      )
      ->addProperty($cgf->codegenProperty('id')->setType('?int')->setValue(12345))
      ->setConstructor(
        $cgf
          ->codegenConstructor()
          ->addParameter('string $text')
          ->setBody('$this->text = $text;'),
      )
      ->addMethod(
        $cgf
          ->codegenMethod('getText')
          ->setIsFinal()
          ->setReturnType('string')
          ->setBody('return $this->text;'),
      )
      ->addMethod(
        $cgf
          ->codegenMethod('genX')
          ->setProtected()
          ->setDocBlock(
            'This is a 76 characters  comment to test the splitting '.
            'based on indentation.',
          )
          ->setReturnType('Awaitable<int>')
          ->setManualBody()
          ->setBody('// your code here'),
      )
      ->setIsFinal()
      ->render();

    $this->assertUnchanged($code);
  }

  public function testLongGeneratedFrom(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenClass('Demo')
      ->addMethod(
        $cgf
          ->codegenMethod('getRawIntEnumCustomTest')
          ->setGeneratedFrom(
            $cgf->codegenGeneratedFromMethodWithKey(
              'EntTestFieldGettersCodegenSchema',
              'getFieldSpecification',
              'RawIntEnumCustomTest',
            ),
          ),
      )
      ->render();

    $this->assertUnchanged($code);
  }

  public function testConstructorWrapperFuncDefault(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenClass('TestWrapperFunc')
      ->setDocBlock(
        "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed ".
        "do eiusmod tempor incididunt ut labore et dolore magna aliqua. ".
        "Ut enim ad minim veniam, quis nostrud exercitation ullamco ".
        "laboris nisi ut aliquip ex ea commodo consequat.\n".
        "Understood?\n".
        "Yes!",
      )
      ->addConstructorWrapperFunc()
      ->render();

    $this->assertUnchanged($code);
  }

  public function testConstructorWrapperFunc(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenClass('TestWrapperFunc')
      ->addProperty($cgf->codegenProperty('text')->setPrivate()->setType('string'))
      ->addProperty(
        $cgf->codegenProperty('hack')->setType('?bool')->setValue(false),
      )
      ->setConstructor(
        $cgf
          ->codegenConstructor()
          ->addParameter('string $text, ?bool $hack')
          ->setBody('$this->text = $text;'),
      )
      ->addConstructorWrapperFunc()
      ->render();

    $this->assertUnchanged($code);
  }

  /*
   * When current class has no constructor specified but its parent class does,
   * we need to specify the parameters of the wrapper function explictly
   *   e.g. parent class StrangeParent has the following constructor
   *        function __construct(string $text) {
   *          // whatever
   *        }
   */
  public function testConstructorWrapperFuncWithExplicitParams(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenClass('TestWrapperFunc')
      ->setExtends('StrangeParent')
      ->addConstructorWrapperFunc(vec[ 'string $text' ])
      ->render();

    $this->assertUnchanged($code);
  }

  public function testExtendsGeneric(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf->codegenClass('Foo')->setExtendsf('X<%s>', 'Y')->render();
    $this->assertContains('extends X<Y>', $code);
  }
}
