/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

interface IHackCodegenConfig {
  public function getFileHeader(): ?Container<string>;

  public function getSpacesPerIndentation(): int;
  public function getMaxLineLength(): int;
  public function shouldUseTabs(): bool;
  public function getRootDir(): string;
  public function getFormatter(): ?ICodegenFormatter;
}
