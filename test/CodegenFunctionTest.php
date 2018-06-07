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

final class CodegenFunctionTest extends CodegenBaseTest {

  public function testSimpleGetter(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenFunction('getName')
      ->setReturnType('string')
      ->setBody('return $name;')
      ->setDocBlock('Return the name of the user.')
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testParams(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenFunction('getName')
      ->addParameter('string $name')
      ->setBody('return $name . $name;')
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testAsync(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenFunction('genFoo')
      ->setIsAsync()
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testMemoize(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenFunction('getExpensive')
      ->setIsMemoized(true)
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testOverride(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenMethod('getNotLikeParent')
      ->setIsOverride(true)
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testOverrideAndMemoized(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenMethod('getExpensiveNotLikeParent')
      ->setIsOverride(true)
      ->setIsMemoized(true)
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testOverrideMemoizedAsync(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenMethod('genExpensiveNotLikeParent')
      ->setIsOverride(true)
      ->setIsMemoized(true)
      ->setIsAsync()
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testSingleUserAttributeWithoutArgument(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenFunction('getTestsBypassVisibility')
      ->addEmptyUserAttribute('TestsBypassVisibility')
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testSingleUserAttributeWithArgument(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenFunction('getUseDataProvider')
      ->addUserAttribute(
        'DataProvider',
        vec['providerFunc'],
        HackBuilderValues::export(),
      )
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testMixedUserAttributes(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenFunction('getBypassVisibilityAndUseDataProvider')
      ->addUserAttribute(
        'DataProvider',
        vec['providerFunc'],
        HackBuilderValues::export(),
      )
      ->addEmptyUserAttribute('TestsBypassVisibility')
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testMixedBuiltInAndUserAttributes(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenMethod('getOverridedBypassVisibilityAndUseDataProvider')
      ->setIsOverride(true)
      ->addUserAttribute(
        'DataProvider',
        vec['providerFunc'],
        HackBuilderValues::export(),
      )
      ->addEmptyUserAttribute('TestsBypassVisibility')
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testMixedBuiltInAndUserAttributesAsync(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenMethod('genOverridedBypassVisibilityAndUseDataProvider')
      ->setIsOverride(true)
      ->addUserAttribute(
        'DataProvider',
        vec['providerFunc'],
        HackBuilderValues::export(),
      )
      ->addEmptyUserAttribute('TestsBypassVisibility')
      ->setIsAsync()
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testManualSection(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenFunction('genProprietorName')
      ->setReturnType('string')
      ->setBody('// insert your code here')
      ->setManualBody()
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testDocBlockCommentsWrap(): void {
    $cgf = $this->getCodegenFactory();
    // 1-3 characters in doc block account for ' * ' in this test.
    $code = $cgf
      ->codegenFunction('getName')
      ->setReturnType('string')
      ->setBody('return $name;')
      // 81 characters
      ->setDocBlock(\str_repeat('x', 78))
      ->setGeneratedFrom($cgf->codegenGeneratedFromClass('EntTestSchema'))
      ->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }
}
