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

final class CodegenFunctionTestCase extends CodegenBaseTest {

  public function testSimpleGetter() {
    $code = codegen_function('getName')
      ->setReturnType('string')
      ->setBody('return $name;')
      ->setDocBlock('Return the name of the user.')
      ->render();
    self::assertUnchanged($code);
  }

  public function testParams() {
    $code = codegen_function('getName')
      ->addParameter('string $name')
      ->setBody('return $name . $name;')
      ->render();
    self::assertUnchanged($code);
  }

  public function testAsync() {
    $code = codegen_function('genFoo')
      ->setIsAsync()
      ->render();
    self::assertUnchanged($code);
  }

  public function testMemoize() {
    $code = codegen_function('getExpensive')
      ->setIsMemoized(true)
      ->render();
    self::assertUnchanged($code);
  }

  public function testOverride() {
    $code = codegen_function('getNotLikeParent')
      ->setIsOverride(true)
      ->render();
    self::assertUnchanged($code);
  }

  public function testOverrideAndMemoized() {
    $code = codegen_function('getExpensiveNotLikeParent')
      ->setIsOverride(true)
      ->setIsMemoized(true)
      ->render();
    self::assertUnchanged($code);
  }

  public function testOverrideMemoizedAsync() {
    $code = codegen_function('genExpensiveNotLikeParent')
      ->setIsOverride(true)
      ->setIsMemoized(true)
      ->setIsAsync()
      ->render();
    self::assertUnchanged($code);
  }

  public function testSingleUserAttributeWithoutArgument() {
    $code = codegen_function('getTestsBypassVisibility')
      ->setUserAttribute(TestsBypassVisibility::class)
      ->render();
    self::assertUnchanged($code);
  }

  public function testSingleUserAttributeWitArgument() {
    $code = codegen_function('getUseDataProvider')
      ->setUserAttribute(DataProvider::class, "'providerFunc'")
      ->render();
    self::assertUnchanged($code);
  }

  public function testMixedUserAttributes() {
    $code = codegen_function('getBypassVisibilityAndUseDataProvider')
      ->setUserAttribute(DataProvider::class, "'providerFunc'")
      ->setUserAttribute(TestsBypassVisibility::class)
      ->render();
    self::assertUnchanged($code);
  }

  public function testMixedBuiltInAndUserAttributes() {
    $code = codegen_function('getOverridedBypassVisibilityAndUseDataProvider')
      ->setIsOverride(true)
      ->setUserAttribute(DataProvider::class, "'providerFunc'")
      ->setUserAttribute(TestsBypassVisibility::class)
      ->render();
    self::assertUnchanged($code);
  }

  public function testMixedBuiltInAndUserAttributesAsync() {
    $code = codegen_function('genOverridedBypassVisibilityAndUseDataProvider')
      ->setIsOverride(true)
      ->setUserAttribute(DataProvider::class, "'providerFunc'")
      ->setUserAttribute(TestsBypassVisibility::class)
      ->setIsAsync()
      ->render();
    self::assertUnchanged($code);
  }

  public function testManualSection() {
    $code = codegen_function('genProprietorName')
      ->setReturnType('string')
      ->setBody('// insert your code here')
      ->setManualBody()
      ->render();
    self::assertUnchanged($code);
  }

  public function testDocBlockCommentsWrap() {
    // 1-3 characters in doc block account for ' * ' in this test.
    $code = codegen_function('getName')
      ->setReturnType('string')
      ->setBody('return $name;')
      // 81 characters
      ->setDocBlock(str_repeat('x', 78))
      ->setGeneratedFrom(codegen_generated_from_class('EntTestSchema'))
      ->render();
    self::assertUnchanged($code);
  }
}
