<?hh // strict
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

namespace Facebook\HackCodegen\_Private\C;

use namespace HH\Lib\C;

function coalescevax<T>(?T ...$in): T {
  $x = C\find($in, $v ==> $v !== null);
  invariant(
    $x !== null,
    'all values are null',
  );
  return $x;
}
