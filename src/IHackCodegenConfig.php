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
 * Implement this interface to define a configuration to generate code.
 * The config will need to be passed to some of the Codegen constructors,
 * which will usually be wrapped in the codegen_* functions.
 */
interface IHackCodegenConfig {
  /**
   * Return an instance of the config, which will usually be a singleton.
   */
  public static function getInstance(): this;

  /**
   * If a non-null value is returned, those lines will be added in the file
   * header.  It can be used for example to write a copyright notice.
   */
  public function getFileHeader(): ?Vector<string>;

  /**
   * How many spaces will each indentation be.
   */
  public function getSpacesPerIndentation(): int;

  /**
   * What's the max length of each line of code.  Some methods will wrap code
   * longer than this value.
   */
  public function getMaxLineLength(): int;

  /**
   * Return the root directory for saving the generated files when relative
   * paths are used.
   */
  public function getRootDir(): string;
}
