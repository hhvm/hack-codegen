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

use function Facebook\HackCodegen\LegacyHelpers\codegen_member_var;

final class CodegenMemberVarTest extends CodegenBaseTest {

  public function testSimple() {
    $code = codegen_member_var('foo')->render();
    $this->assertUnchanged($code);
  }

  public function testPublicStatic() {
    $code = codegen_member_var('foo')
      ->setPublic()
      ->setIsStatic()
      ->render();

    $this->assertUnchanged($code);
  }

  public function testTyped() {
    $code = codegen_member_var('name')
      ->setProtected()
      ->setType('string')
      ->render();

    $this->assertUnchanged($code);
  }

  public function testTypedWithFalsyValue() {
    $code = codegen_member_var('count')
      ->setType('?int')
      ->setValue(0)
      ->render();

    $this->assertUnchanged($code);
  }

  public function testArrayValues() {
    $code = codegen_member_var('languages')
      ->setIsStatic()
      ->setValue(array('en' => 'English', 'es' => 'Spanish', 'fr' => 'French'))
      ->render();

    $this->assertUnchanged($code);
  }

  public function testVector() {
    $code = codegen_member_var('parameters')
      ->setType('Vector<string>')
      ->setLiteralValue('Vector {}')
      ->render();

    $this->assertUnchanged($code);
  }

  public function testDocBlock() {
    $code = codegen_member_var('thingWithComment')
      ->setInlineComment('a comment')
      ->render();

    $this->assertUnchanged($code);
  }

}
