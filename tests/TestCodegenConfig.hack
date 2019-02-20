/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

final class TestCodegenConfig implements IHackCodegenConfig {

  public function getFileHeader(): ?Vector<string> {
    return Vector { 'Codegen Tests' };
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
    return __DIR__;
  }

  public function getFormatter(): ?ICodegenFormatter {
    return null;
  }
}
