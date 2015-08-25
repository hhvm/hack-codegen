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

/**
 * This class contains the default configuration for Hack code generation.
 * Please update it to your needs.
 * Notice that if you need, you can have more than 1 configuration,
 * and you'll need to explicitly pass an instance to the constructor
 * of some of the classes, e.g. HackBuilder.
 */
final class HackCodegenConfig implements IHackCodegenConfig {

  <<__Memoize>>
  public static function getInstance(): this {
    return new static();
  }

  public function getFileHeader(): ?Vector<string> {
    // If you want a header on each generated file, insert it here.
    return null;
  }

  public function getSpacesPerIndentation(): int {
    return 2;
  }

  public function getMaxLineLength(): int {
    return 80;
  }

  public function getRootDir(): string {
    return __DIR__;
  }
}
