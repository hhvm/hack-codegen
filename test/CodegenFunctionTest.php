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

use function Facebook\HackCodegen\LegacyHelpers\{
  codegen_function,
  codegen_generated_from_class
};

final class CodegenFunctionTest extends CodegenBaseTest {

  public function testSimpleGetter(): void {
    $code = codegen_function('getName')
      ->setReturnType('string')
      ->setBody('return $name;')
      ->setDocBlock('Return the name of the user.')
      ->render();
    $this->assertUnchanged($code);
  }

  public function testParams(): void {
    $code = codegen_function('getName')
      ->addParameter('string $name')
      ->setBody('return $name . $name;')
      ->render();
    $this->assertUnchanged($code);
  }

  public function testAsync(): void {
    $code = codegen_function('genFoo')
      ->setIsAsync()
      ->render();
    $this->assertUnchanged($code);
  }

  public function testMemoize(): void {
    $code = codegen_function('getExpensive')
      ->setIsMemoized(true)
      ->render();
    $this->assertUnchanged($code);
  }

  public function testOverride(): void {
    $code = codegen_function('getNotLikeParent')
      ->setIsOverride(true)
      ->render();
    $this->assertUnchanged($code);
  }

  public function testOverrideAndMemoized(): void {
    $code = codegen_function('getExpensiveNotLikeParent')
      ->setIsOverride(true)
      ->setIsMemoized(true)
      ->render();
    $this->assertUnchanged($code);
  }

  public function testOverrideMemoizedAsync(): void {
    $code = codegen_function('genExpensiveNotLikeParent')
      ->setIsOverride(true)
      ->setIsMemoized(true)
      ->setIsAsync()
      ->render();
    $this->assertUnchanged($code);
  }

  public function testSingleUserAttributeWithoutArgument(): void {
    $code = codegen_function('getTestsBypassVisibility')
      ->setUserAttribute('TestsBypassVisibility')
      ->render();
    $this->assertUnchanged($code);
  }

  public function testSingleUserAttributeWithArgument(): void {
    $code = codegen_function('getUseDataProvider')
      ->setUserAttribute('DataProvider', "'providerFunc'")
      ->render();
    $this->assertUnchanged($code);
  }

  public function testMixedUserAttributes(): void {
    $code = codegen_function('getBypassVisibilityAndUseDataProvider')
      ->setUserAttribute('DataProvider', "'providerFunc'")
      ->setUserAttribute('TestsBypassVisibility')
      ->render();
    $this->assertUnchanged($code);
  }

  public function testMixedBuiltInAndUserAttributes(): void {
    $code = codegen_function('getOverridedBypassVisibilityAndUseDataProvider')
      ->setIsOverride(true)
      ->setUserAttribute('DataProvider', "'providerFunc'")
      ->setUserAttribute('TestsBypassVisibility')
      ->render();
    $this->assertUnchanged($code);
  }

  public function testMixedBuiltInAndUserAttributesAsync(): void {
    $code = codegen_function('genOverridedBypassVisibilityAndUseDataProvider')
      ->setIsOverride(true)
      ->setUserAttribute('DataProvider', "'providerFunc'")
      ->setUserAttribute('TestsBypassVisibility')
      ->setIsAsync()
      ->render();
    $this->assertUnchanged($code);
  }

  public function testManualSection(): void {
    $code = codegen_function('genProprietorName')
      ->setReturnType('string')
      ->setBody('// insert your code here')
      ->setManualBody()
      ->render();
    $this->assertUnchanged($code);
  }

  public function testDocBlockCommentsWrap(): void {
    // 1-3 characters in doc block account for ' * ' in this test.
    $code = codegen_function('getName')
      ->setReturnType('string')
      ->setBody('return $name;')
      // 81 characters
      ->setDocBlock(str_repeat('x', 78))
      ->setGeneratedFrom(codegen_generated_from_class('EntTestSchema'))
      ->render();
    $this->assertUnchanged($code);
  }
}
