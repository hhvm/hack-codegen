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

use namespace HH\Lib\{Str, Vec};

final class HackfmtFormatter implements ICodegenFormatter {

  public function __construct(
    private IHackCodegenConfig $config
  ) {}

  public function format(
    string $code,
    string $_file_name,
  ): string {
    $output = array();
    $exit_code = null;

    $tempnam = \tempnam(
      \sys_get_temp_dir(),
      'hack-codegen-hackfmt',
    );

    $options = $this->getFormattedOptions();

    try {
      \file_put_contents($tempnam, $code);
      \exec(
        'hackfmt '.$options.' '.\escapeshellarg($tempnam),
        &$output,
        &$exit_code,
      );
    } finally {
      \unlink($tempnam);
    }

    invariant(
      $exit_code === 0,
      'Failed to invoke hackfmt',
    );
    return \implode("\n", $output)."\n";
  }

  <<__Memoize>>
  private function getFormattedOptions(): string {
    $options = vec[
      '--indent-width',
      (string) $this->config->getSpacesPerIndentation(),
      '--line-width',
      (string) $this->config->getMaxLineLength(),
    ];

    if ($this->config->shouldUseTabs()) {
      $options[] = '--tabs';
    }

    return Vec\map(
      $options,
      $option ==> \escapeshellarg($option),
    ) |> Str\join($$, " ");
  }
}
