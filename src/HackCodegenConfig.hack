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
 * This class contains the default configuration for Hack code generation.
 */
final class HackCodegenConfig implements IHackCodegenConfig {
  private string $rootDir = __DIR__;
  private ?ICodegenFormatter $formatter = null;

  public function getFileHeader(): ?vec<string> {
    // If you want a header on each generated file, insert it here.
    return null;
  }

  public function getSpacesPerIndentation(): int {
    return 2;
  }

  public function getMaxLineLength(): int {
    return 80;
  }

  public function shouldUseTabs(): bool {
    return false;
  }

  public function getRootDir(): string {
    return $this->rootDir;
  }

  public function getFormatter(): ?ICodegenFormatter {
    return $this->formatter;
  }

  public function withRootDir(string $root): this {
    $out = clone $this;
    $out->rootDir = $root;
    return $out;
  }

  public function withFormatter(ICodegenFormatter $formatter): this {
    $out = clone $this;
    $out->formatter = $formatter;
    return $out;
  }
}
