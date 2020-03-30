/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

final class CodegenInterfaceTest extends CodegenBaseTest {

  public function testEmptyInterface(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf->codegenInterface('IEmpty')->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testExtendsInterfaces(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenInterface('IExtenderOfTwo')
      ->addInterface($cgf->codegenImplementsInterface('IExtended'))
      ->addInterface($cgf->codegenImplementsInterface('IOtherExtended'))
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testExtendsInterfaceWithGeneratedFrom(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenInterface('IExtenderOfOne')
      ->addInterface(
        $cgf
          ->codegenImplementsInterface('IExtended')
          ->setGeneratedFrom($cgf->codegenGeneratedFromMethod('Foo', 'Bar')),
      )
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testInterfaceWithStuff(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenInterface('IInterfaceWithStuff')
      ->addMethod(
        $cgf
          ->codegenMethod('genFoo')
          ->setReturnType('Awaitable<mixed>')
          ->setDocBlock('Override this to have the stuff'),
      )
      ->addConstant(
        $cgf->codegenClassConstant('A_CONST')
          ->setValue(0, HackBuilderValues::export()),
      )
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testInterfaceWithGenerics(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenInterface('IInterfaceWithGenerics')
      ->addGenerics(Vector { 'TKey', 'TObject' })
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }
}
