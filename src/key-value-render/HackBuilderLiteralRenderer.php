<?hh // strict
/*
 *  Copyright (c) 2015, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the BSD-style license found in the
 *  LICENSE file in the root directory of this source tree. An additional grant
 *  of patent rights can be found in the PATENTS file in the same directory.
 *
 */

namespace Facebook\HackCodegen;

final class HackBuilderLiteralRender
implements IHackBuilderKeyRenderer<string>, IHackBuilderValueRenderer<string> {
  final public function render(
    IHackCodegenConfig $_,
    string $value,
  ): string {
    return $value;
  }
}
