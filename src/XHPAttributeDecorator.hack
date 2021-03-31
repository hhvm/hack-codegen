/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

enum XHPAttributeDecorator: int {
  REQUIRED = 0;
  LATE_INIT = 1;
}

function xhp_attribute_decorator_to_string(
  XHPAttributeDecorator $decorator,
): string {
  switch ($decorator) {
    case XHPAttributeDecorator::REQUIRED:
      return '@required';
    case XHPAttributeDecorator::LATE_INIT:
      return '@lateinit';
  }
}
