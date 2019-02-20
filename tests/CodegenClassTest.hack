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

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testExtendsAndFinal(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenClass('NothingHere')
      ->setExtends('NothingHereBase')
      ->addInterface($cgf->codegenImplementsInterface('JustOneInterface'))
      ->setIsFinal()
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
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

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testMultipleInterfaces(): void {
    $interfaces = Vector { 'IHarryPotter', 'IHermioneGranger', 'IRonWeasley' };

    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenClass('JKRowling')
      ->addInterfaces($cgf->codegenImplementsInterfaces($interfaces))
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testLongClassDeclaration(): void {
    // The class declaration is just long enough (82 chars) to make it wrap
    $code = $this
      ->getCodegenFactory()
      ->codegenClass('ClassWithReallyLongName')
      ->setExtends('NowThisIsTheParentClassWithALongNameItSelf')
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testLongClassDeclarationWithInterfaces(): void {
    $interfaces = Vector { 'InterfaceUno', 'InterfaceDos', 'InterfaceTres' };
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenClass('ClassWithReallyReallyLongName')
      ->setExtends('NowThisIsTheParentClassWithALongNameItSelf')
      ->addInterfaces($cgf->codegenImplementsInterfaces($interfaces))
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testClassDeclarationWithGenerics(): void {
    $generics_decl =
      vec['Tent as Ixyz', 'T', 'Tstory as EntCreationStory<Tent>'];

    $code = $this
      ->getCodegenFactory()
      ->codegenClass('ClassWithGenerics')
      ->addGenerics($generics_decl)
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
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
      ->addConstant(
        $cgf->codegenClassConstant('MAX_SIZE')
          ->setValue(256, HackBuilderValues::export()),
      )
      ->addConstant(
        $cgf->codegenClassConstant('DEFAULT_NAME')
          ->setValue('MyEnt', HackBuilderValues::export())
          ->setDocBlock('Default name of Ent.'),
      )
      ->addConstant(
        $cgf->codegenClassConstant('PI')
          ->setValue(3.1415, HackBuilderValues::export()),
      )
      ->setHasManualMethodSection()
      ->setHasManualDeclarations()
      ->addProperty(
        $cgf->codegenProperty('text')->setProtected()->setType('string'),
      )
      ->addProperty(
        $cgf->codegenProperty('id')
          ->setType('?int')
          ->setValue(12345, HackBuilderValues::export()),
      )
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

    expect_with_context(static::class, $code)->toBeUnchanged();
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

    expect_with_context(static::class, $code)->toBeUnchanged();
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

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testConstructorWrapperFunc(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenClass('TestWrapperFunc')
      ->addProperty(
        $cgf->codegenProperty('text')->setPrivate()->setType('string'),
      )
      ->addProperty(
        $cgf
          ->codegenProperty('hack')
          ->setType('?bool')
          ->setValue(false, HackBuilderValues::export()),
      )
      ->setConstructor(
        $cgf
          ->codegenConstructor()
          ->addParameter('string $text, ?bool $hack')
          ->setBody('$this->text = $text;'),
      )
      ->addConstructorWrapperFunc()
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
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
      ->addConstructorWrapperFunc(vec['string $text'])
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testExtendsGeneric(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf->codegenClass('Foo')->setExtendsf('X<%s>', 'Y')->render();

    expect($code)->toContain('extends X<Y>');
  }

  public function testGenericsWithSubtypeConstraints(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf->codegenClass('GenericsTestClass')
      ->addGenericSubtypeConstraint('T', 'U')
      ->render();

    expect($code)->toContain('T as U');
  }

  public function testGenericsWithSuperTypeConstraints(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf->codegenClass('GenericsTestClass')
      ->addGenericSupertypeConstraint('T', 'U')
      ->render();

    expect($code)->toContain('T super U');
  }

  public function testGenericsWithConstraints(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenClass('GenericsTestClass')
      ->addGenericSubtypeConstraint('Tk', 'Tv')
      ->addGenericSupertypeConstraint('Tu', 'Sp')
      ->addGenericSubtypeConstraint('Tt', 'Xx')
      ->addGeneric('Tsingle')
      ->render();

    expect($code)->toContain('Tk as Tv');
    expect($code)->toContain('Tu super Sp');
    expect($code)->toContain('Tt as Xx');
    expect($code)->toContain('Tsingle');
  }
}
