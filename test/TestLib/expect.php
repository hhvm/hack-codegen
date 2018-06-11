<?hh // strict
/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

function expect<T>(T $obj, mixed ...$args): ExpectObj<T> {
  return new ExpectObj(new ImmVector(\func_get_args()));
}

function expect_with_context<T>(string $context, T $obj, mixed ...$args): ExpectObj<T> {
  return new ExpectObj(new ImmVector(\array_slice(\func_get_args(), 1)), $context);
}
