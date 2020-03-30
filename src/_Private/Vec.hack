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

/**
 * Return and remove the last element from a container.
 *
 * @throws an exception if the container is empty
 * @return the last element in the container
 */
function pop_backx<T>(inout Container<T> $list): T {
  invariant(
    !C\is_empty($list),
    '%s called, but container is empty',
    __FUNCTION__,
  );
  $last = \array_pop(inout $list);
  return $last;
}
