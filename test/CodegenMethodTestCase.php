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

final class CodegenMethodTestCase extends CodegenBaseTest {

  public function testSimpleGetter() {
    $code = codegen_method('getName')
      ->setReturnType('string')
      ->setBody('return $this->name;')
      ->setDocBlock('Return the name of the user.')
      ->render();

    self::assertUnchanged($code);
  }

  public function testAbstractProtectedAndParams() {
    $code = codegen_method('getSchema')
      ->addParameter('string $name')
      ->setIsAbstract()
      ->setProtected()
      ->render();

    self::assertUnchanged($code);
 }

  public function testAsync() {
    $code = codegen_method('genFoo')
      ->setIsAsync()
      ->render();

    self::assertUnchanged($code);
  }

  public function testPrivateAndStaticWithEmptyBody() {
    $code = codegen_method('doNothing')
      ->setIsStatic()
      ->setPrivate()
      ->render();

    self::assertUnchanged($code);
  }

  public function testManualSection() {
    $method = codegen_method('genProprietorName')
      ->setReturnType('string')
      ->setBody('// insert your code here')
      ->setManualBody();

    codegen_class(EntBlindPig::class)->addMethod($method);
    $code = $method->render();

    self::assertUnchanged($code);
  }

  public function testConstructor() {
    $code = codegen_constructor()
      ->addParameter('string $name')
      ->setBody('$this->name = $name;')
      ->render();

    self::assertUnchanged($code);
  }

  public function testDocBlockCommentsWrap() {
    // 1-3 characters in doc block account for ' * ' in this test.
    $code = codegen_method('getName')
      ->setReturnType('string')
      ->setBody('return $this->name;')
      // 81 characters
      ->setDocBlock(str_repeat('x', 78))
      ->setGeneratedFrom(
        codegen_generated_from_method_with_key(
          'EntTestSchema',
          'getFields',
          'name'
        )
      )->render();

    self::assertUnchanged($code);
  }
}
