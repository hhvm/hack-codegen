/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

interface ICodeBuilderRenderer {
  /**
   * Appends the code of this class to the builder
   */
  public function appendToBuilder(HackBuilder $builder): HackBuilder;

  public function render(?HackBuilder $builder = null): string;
}
