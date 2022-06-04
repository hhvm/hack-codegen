/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use namespace HH\Lib\{Str, Vec};

final class HackfmtFormatter implements ICodegenFormatter {

  public function __construct(private IHackCodegenConfig $config) {}

  public function format(string $code, string $_file_name): string {
    $output = vec[];
    $exit_code = null;

    $tempnam = \tempnam(\sys_get_temp_dir(), 'hack-codegen-hackfmt').'.hack';

    $options = $this->getFormattedOptions();

    try {
      \file_put_contents($tempnam, $code);
      \exec(
        'hackfmt '.$options.' '.\escapeshellarg($tempnam),
        inout $output,
        inout $exit_code,
      );
    } finally {
      \unlink($tempnam);
    }

    invariant($exit_code === 0, 'Failed to invoke hackfmt');
    return Str\join($output, "\n")."\n";
  }

  <<__Memoize>>
  private function getFormattedOptions(): string {
    $options = vec[
      '--indent-width',
      (string)$this->config->getSpacesPerIndentation(),
      '--line-width',
      (string)$this->config->getMaxLineLength(),
    ];

    if ($this->config->shouldUseTabs()) {
      $options[] = '--tabs';
    }

    // HHVM < 4.48 always formats generated code. HHVM 4.48 never does (so this
    // formatter is broken on that version). HHVM >= 4.49 formats generated code
    // iff the following flag is provided:
    if (\version_compare(\HHVM_VERSION, '4.49') >= 0) {
      $options[] = '--format-generated-code';
    }

    return Vec\map($options, \escapeshellarg<>) |> Str\join($$, ' ');
  }
}
