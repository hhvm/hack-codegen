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

abstract class CodegenBaseTest {

  public static function assertTrue(
    bool $value,
    string $message = 'Expected true',
  ): void {
    invariant($value, $message);
  }

  public static function assertFalse(
    bool $value,
    string $message = 'Expected false',
  ): void {
    invariant(!$value, $message);
  }

  public static function assertEquals(
    mixed $expected,
    mixed $actual,
    ?string $message = null,
  ): void {
    if ($message === null) {
       $message = "Expected ".print_r($expected, true).
         " but got ".print_r($actual, true). " instead";
    }
    invariant($expected === $actual, $message);
  }


  public static function assertUnchanged(
    string $value,
    ?string $token = null,
  ): void {
    $class_name = get_called_class();
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
