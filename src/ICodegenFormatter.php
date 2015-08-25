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
 * Implement this interface to define a class to format a file after the
 * code is generated.
 * In order to using the formatting, in the instance of codegen_file, call
 * setFormatter with an instance of a formatter.
 */
interface ICodegenFormatter {
  public function format(string $code, string $file_name): string;
}
