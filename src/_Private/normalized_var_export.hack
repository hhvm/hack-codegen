/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen\_Private;

/** `var_export()`, normalized to produce valid Hack code, rather than PHP.
 *
 * This includes changes such as:
 * - `null` instead of `NULL`
 * - `vec` instead of `HH\vec`
 */
function normalized_var_export(mixed $value): string {
  if ($value === null) {
    // var_export capitalizes NULL
    return 'null';
  }
  return strip_hh_prefix(\var_export($value, true));
}
