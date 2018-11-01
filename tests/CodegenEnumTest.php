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

final class CodegenEnumTest extends CodegenBaseTest {

  public function testDocblock(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenEnum('TestDocblock', 'int')
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

  public function testIsAs(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenEnum('NothingHere', 'int')
      ->setIsAs('int')
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testLongEnumDeclaration(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenEnum('EnumWithReallyLongName', 'string')
      ->setIsAs('NowThisIsTheParentEnumWithALongNameItSelf')
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testDemo(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenEnum('Demo', 'string')
      ->addMember(
        $cgf->codegenEnumMember('A')
          ->setValue('a', HackBuilderValues::export()),
      )
      ->addMember(
        $cgf->codegenEnumMember('B')
          ->setValue('b', HackBuilderValues::export())
          ->setDocBlock('This is a different letter'),
      )
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }
}
