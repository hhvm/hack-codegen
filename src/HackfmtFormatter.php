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

use namespace HH\Lib\Str;

final class HackfmtFormatter implements ICodegenFormatter {
  public function format(
    string $code,
    string $file_name,
  ): string {
    $output = array();
    $exit_code = null;

    $tempnam = tempnam(
      sys_get_temp_dir(),
      'hack-codegen-hackfmt',
    );
    try {
      file_put_contents($tempnam, $code);
      exec(
        'hackfmt '.escapeshellarg($tempnam),
        &$output,
        &$exit_code,
      );
    } finally {
      unlink($tempnam);
    }

    invariant(
      $exit_code === 0,
      'Failed to invoke hackfmt',
    );
    return implode("\n", $output)."\n";
  }
}
