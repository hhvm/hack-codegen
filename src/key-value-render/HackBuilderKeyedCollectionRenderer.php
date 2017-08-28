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

class HackBuilderKeyedCollectionRenderer<Tk as arraykey, T as Traversable<Tk>>
  implements IHackBuilderValueRenderer<T> {
  public function __construct(
    private classname<T> $containerName,
    private IHackBuilderKeyRenderer<Tk> $keyRenderer,
  ) {
  }

  final public function render(IHackCodegenConfig $config, T $values): string {
    $keyRenderer = $this->keyRenderer;
    $builder = (new HackBuilder($config))
      ->add(strip_hh_prefix($this->containerName))
      ->openBracket();
    foreach ($values as $value) {
      $builder->addLinef('%s,', $keyRenderer->render($config, $value));
    }
    return $builder->closeBracket()->getCode();
  }
}
