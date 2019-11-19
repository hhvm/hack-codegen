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
  IHackBuilderKeyRenderer,
  IHackCodegenConfig,
};

final class HackBuilderKeyLambdaRenderer<T as arraykey>
  implements IHackBuilderKeyRenderer<T> {
  public function __construct(
    private (function(IHackCodegenConfig, T): string) $callback,
  ) {
  }

  public function render(IHackCodegenConfig $config, T $value): string {
    $callback = $this->callback;
    return $callback($config, $value);
  }
}
