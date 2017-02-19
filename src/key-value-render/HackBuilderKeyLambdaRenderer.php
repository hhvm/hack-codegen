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

final class HackBuilderKeyLambdaRenderer<T as arraykey>
implements IHackBuilderKeyRenderer<T> {
  public function __construct(
    private (function(IHackCodegenConfig, T):string) $callback,
  ) {
  }

  final public function render(
    IHackCodegenConfig $config,
    T $value,
  ): string {
    $callback = $this->callback;
    return $callback($config, $value);
  }
}
