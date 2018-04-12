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

final class HackBuilderValueExportRenderer
  implements IHackBuilderValueRenderer<mixed> {
  final public function render(IHackCodegenConfig $_, mixed $value): string {
    return normalized_var_export($value);
  }
}
