<?hh // strict
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

namespace Facebook\HackCodegen;

function normalized_var_export(mixed $value): string {
  if ($value === null) {
    // var_export capitalizes NULL
    return 'null';
  }
  return strip_hh_prefix(var_export($value, true));
}
