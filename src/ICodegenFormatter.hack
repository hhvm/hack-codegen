/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

/**
 * Implement this interface to define a class to format a file after the
 * code is generated.
 */
interface ICodegenFormatter {
  public function format(string $code, string $file_name): string;
}
