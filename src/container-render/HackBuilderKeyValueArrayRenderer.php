<?hh // strict
/*
 *  Copyright (c) 2015, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the BSD-style license found in the
 *  LICENSE file in the root directory of this source tree. An additional grant
 *  of patent rights can be found in the PATENTS file in the same directory.
 *
 */

namespace Facebook\HackCodegen;

final class HackBuilderKeyValueArrayRenderer<Tk as arraykey,Tv>
implements IHackBuilderValueRenderer<array<Tk,Tv>> {
  public function __construct(
    private IHackBuilderKeyRenderer<Tk> $keyRenderer,
    private IHackBuilderValueRenderer<Tv> $valueRenderer,
  ) {
  }

  final public function render(
    HackCodegenConfig $config,
    array<Tk,Tv> $array,
  ): string {
    $key_renderer = $this->keyRenderer;
    $value_renderer = $this->valueRenderer;
    $builder = (new HackBuilder($config))
      ->addLine('array(')
      ->indent();
    foreach ($array as $key => $value) {
      $builder->addWithSuggestedLineBreaksf(
        "%s =>\t%s,",
        $key_renderer->render($config, $key),
        $value_renderer->render($config, $value),
      );
    }
    return $builder
      ->unindent()
      ->add(')')
      ->getCode();
  }
}
