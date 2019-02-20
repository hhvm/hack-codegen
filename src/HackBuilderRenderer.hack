/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

/**
 * Use this trait along with an implementation of ICodeBuilderRenderer.
 */
trait HackBuilderRenderer {
  require implements ICodeBuilderRenderer;

  protected IHackCodegenConfig $config;

  final public function render(?HackBuilder $builder = null): string {
    if ($builder === null) {
      $builder = new HackBuilder($this->config);
    }
    return $this->appendToBuilder($builder)->getCode();
  }
}
