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

final class HackBuilderValueArrayRenderer<Tv>
  implements IHackBuilderValueRenderer<array<Tv>> {
  public function __construct(
    private IHackBuilderValueRenderer<Tv> $valueRenderer,
  ) {
  }

  final public function render(
    IHackCodegenConfig $config,
    array<Tv> $array,
  ): string {
    $value_renderer = $this->valueRenderer;
    $builder = (new HackBuilder($config))->addLine('array(')->indent();
    foreach ($array as $value) {
      $builder->addLinef('%s,', $value_renderer->render($config, $value));
    }
    return $builder->unindent()->add(')')->getCode();
  }
}
