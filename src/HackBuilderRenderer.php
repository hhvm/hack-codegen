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

/**
 * Use this trait along with an implementation of ICodeBuilderRenderer.
 */
trait HackBuilderRenderer {
  require implements ICodeBuilderRenderer;

  final public function render(?HackBuilder $builder = null): string {
    if ($builder !== null) {
      return $this->appendToBuilder($builder)->getCode();
    } else {
      return $this->appendToBuilder(hack_builder())->getCode();
    }
  }
}
