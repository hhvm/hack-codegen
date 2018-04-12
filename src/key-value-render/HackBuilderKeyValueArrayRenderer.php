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

final class HackBuilderKeyValueArrayRenderer<Tk as arraykey, Tv>
  implements IHackBuilderValueRenderer<array<Tk, Tv>> {
  public function __construct(
    private string $keyword,
    private IHackBuilderKeyRenderer<Tk> $keyRenderer,
    private IHackBuilderValueRenderer<Tv> $valueRenderer,
  ) {
  }

  final public function render(
    IHackCodegenConfig $config,
    array<Tk, Tv> $array,
  ): string {
    $key_renderer = $this->keyRenderer;
    $value_renderer = $this->valueRenderer;
    $builder =
      (new HackBuilder($config))->addLinef('%s(', $this->keyword)->indent();
    foreach ($array as $key => $value) {
      $builder->addWithSuggestedLineBreaksf(
        "%s =>\t%s,\n",
        $key_renderer->render($config, $key),
        $value_renderer->render($config, $value),
      );
    }
    return $builder->unindent()->add(')')->getCode();
  }
}
