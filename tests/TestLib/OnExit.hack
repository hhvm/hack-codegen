/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

final class OnExit implements \IDisposable {
  const type TCallback = (function():void);
  public function __construct(private self::TCallback $cb) {
  }

  public function __dispose(): void {
    $cb = $this->cb;
    $cb();
  }
}

<<__ReturnDisposable>>
function OnExit(OnExit::TCallback $cb): OnExit {
  return new OnExit($cb);
}
