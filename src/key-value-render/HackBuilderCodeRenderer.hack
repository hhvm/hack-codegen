/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen\_Private;

use type Facebook\HackCodegen\{
  HackBuilder,
  IHackBuilderValueRenderer,
  IHackCodegenConfig,
};

final class HackBuilderCodeRenderer
implements IHackBuilderValueRenderer<HackBuilder> {

  public function render(
    IHackCodegenConfig $_,
    HackBuilder $value,
  ): string {
    return $value->getCode();
  }
}
