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

enum HackBuilderValues : int {
  // The value will be used literally, which is useful for example when
  // passing a constant such as MyEnum::Value
  LITERAL = 1;
  // The value will be exported to be rendered according the type.  E.g. an int
  // will be rendered without changes but a string will be rendered with quotes.
  EXPORT = 2;
}
