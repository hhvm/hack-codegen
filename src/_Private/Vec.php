<?hh // strict
/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen\_Private\Vec;

use namespace HH\Lib\C;

function pop_back<T>(Container<T> $list): (vec<T>, ?T) {
  $last = \array_pop(&$list);
  return tuple(vec($list), $last);
}

function pop_backx<T>(Container<T> $list): (vec<T>, T) {
  $list = vec($list);
  $last = C\lastx($list);
  \array_pop(&$list);
  return tuple(vec($list), $last);
}
