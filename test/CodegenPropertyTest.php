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

final class CodegenPropertyTest extends CodegenBaseTest {

  public function testSimple(): void {
    $code = $this->getCodegenFactory()->codegenProperty('foo')->render();
    $this->assertUnchanged($code);
  }

  public function testPublicStatic(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenProperty('foo')
      ->setPublic()
      ->setIsStatic()
      ->render();

    $this->assertUnchanged($code);
  }

  public function testTyped(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenProperty('name')
      ->setProtected()
      ->setType('string')
      ->render();

    $this->assertUnchanged($code);
  }

  public function testTypedWithFalsyValue(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenProperty('count')
      ->setType('?int')
      ->setValue(0)
      ->render();

    $this->assertUnchanged($code);
  }

  public function testArrayValues(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenProperty('languages')
      ->setIsStatic()
      ->setValue(array('en' => 'English', 'es' => 'Spanish', 'fr' => 'French'))
      ->render();

    $this->assertUnchanged($code);
  }

  public function testVector(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenProperty('parameters')
      ->setType('Vector<string>')
      ->setLiteralValue('Vector {}')
      ->render();

    $this->assertUnchanged($code);
  }

  public function testDocBlock(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenProperty('thingWithComment')
      ->setInlineComment('a comment')
      ->render();

    $this->assertUnchanged($code);
  }

}
