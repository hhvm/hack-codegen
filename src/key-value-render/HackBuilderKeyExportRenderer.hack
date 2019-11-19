/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen\_Private;

use type Facebook\HackCodegen\{
  IHackBuilderKeyRenderer,
  IHackCodegenConfig,
};

final class HackBuilderKeyExportRenderer
  implements IHackBuilderKeyRenderer<arraykey> {
  public function render(IHackCodegenConfig $_, arraykey $value): string {
    return normalized_var_export($value);
  }
}
