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
  * Interface for converting a value into code, when the value is required
  * to be a valid `arraykey`.
  *
  * This does not extend `IHackBuilderValueRenderer` so ensure that callsites
  * explictly specify which renderer is the key renderer, and which is the
  * value renderer.
  */
interface IHackBuilderKeyRenderer<-T as arraykey> {
  public function render(IHackCodegenConfig $config, T $input): string;
}
