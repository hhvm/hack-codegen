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

final class TestCodegenConfig implements IHackCodegenConfig {

  public function getFileHeader(): ?vec<string> {
    return vec [ 'Codegen Tests' ];
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
