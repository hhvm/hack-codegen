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

final class CodegenEnumTestCase extends CodegenBaseTest {

  public function testDocblock() {
    $code = codegen_enum('TestDocblock', 'int')
      ->setDocBlock(
        "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed ".
        "do eiusmod tempor incididunt ut labore et dolore magna aliqua. ".
        "Ut enim ad minim veniam, quis nostrud exercitation ullamco ".
        "laboris nisi ut aliquip ex ea commodo consequat.\n".
        "Understood?\n".
        "Yes!"
      )
      ->render();

    self::assertUnchanged($code);
  }

  public function testIsAs() {
    $code = codegen_enum('NothingHere', 'int')
      ->setIsAs('int')
      ->render();

    self::assertUnchanged($code);
  }

  public function testLongEnumDeclaration() {
    $code = codegen_enum('EnumWithReallyLongName', 'string')
      ->setIsAs('NowThisIsTheParentEnumWithALongNameItSelf')
      ->render();

    self::assertUnchanged($code);
  }

  public function testDemo() {
    $code = codegen_enum('Demo', 'string')
      ->addConst('A', 'a')
      ->addConst('B', 'b', 'This is a different letter')
      ->render();

    self::assertUnchanged($code);
  }
}
