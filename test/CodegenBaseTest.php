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

abstract class CodegenBaseTest extends \Facebook\HackTest\HackTest {

  protected function getCodegenFactory(): HackCodegenFactory {
    return new HackCodegenFactory(new TestCodegenConfig());
  }

  public function assertUnchanged(string $value, ?string $token = null): void {
    $class_name = \get_called_class();
    $path = CodegenExpectedFile::getPath($class_name);
    $expected = CodegenExpectedFile::parseFile($path);
    $token = $token === null ? CodegenExpectedFile::findToken() : $token;

    if ($expected->contains($token) && $expected[$token] === $value) {
      return;
    }

    $new_expected = clone $expected;
    $new_expected[$token] = $value;
    CodegenExpectedFile::writeExpectedFile($path, $new_expected, $expected);

    $expected = CodegenExpectedFile::parseFile($path);
    invariant(
      $expected->contains($token) && $expected[$token] === $value,
      'New value not accepted by user',
    );
  }
}
