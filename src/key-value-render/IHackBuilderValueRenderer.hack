/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

/** Base interface for rendering a value to code */
interface IHackBuilderValueRenderer<-T> {
  /** Convert `$input` into code */
  public function render(IHackCodegenConfig $config, T $input): string;
}
