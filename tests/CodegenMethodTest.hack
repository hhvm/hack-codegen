/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use namespace HH\Lib\{Str};

final class CodegenMethodTest extends CodegenBaseTest {

  public function testSimpleGetter(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenMethod('getName')
      ->setReturnType('string')
      ->setBody('return $this->name;')
      ->setDocBlock('Return the name of the user.')
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testAbstractProtectedAndParams(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenMethod('getSchema')
      ->addParameter('string $name')
      ->setIsAbstract()
      ->setProtected()
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testAsync(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf->codegenMethod('genFoo')->setIsAsync()->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testPrivateAndStaticWithEmptyBody(): void {
    $cgf = $this->getCodegenFactory();
    $code =
      $cgf->codegenMethod('doNothing')->setIsStatic()->setPrivate()->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testManualSection(): void {
    $cgf = $this->getCodegenFactory();
    $method = $cgf
      ->codegenMethod('genProprietorName')
      ->setReturnType('string')
      ->setBody('// insert your code here')
      ->setManualBody();

    $cgf->codegenClass('MyClass')->addMethod($method);
    $code = $method->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testConstructor(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenConstructor()
      ->addParameter('string $name')
      ->setBody('$this->name = $name;')
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testDocBlockCommentsWrap(): void {
    $cgf = $this->getCodegenFactory();
    // 1-3 characters in doc block account for ' * ' in this test.
    $code = $cgf
      ->codegenMethod('getName')
      ->setReturnType('string')
      ->setBody('return $this->name;')
      // 81 characters
      ->setDocBlock(Str\repeat('x', 78))
      ->setGeneratedFrom(
        $cgf->codegenGeneratedFromMethodWithKey(
          'EntTestSchema',
          'getFields',
          'name',
        ),
      )
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }
}
