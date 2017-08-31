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

class HackBuilderNativeValueCollectionRenderer<Tv, T as Traversable<Tv>>
  implements IHackBuilderValueRenderer<T> {
  public function __construct(
    private ContainerType $container,
    private IHackBuilderValueRenderer<Tv> $valueRenderer,
  ) {
  }

  final public function render(IHackCodegenConfig $config, T $values): string {
    $value_renderer = $this->valueRenderer;
    $builder = (new HackBuilder($config))->openContainer($this->container);
    foreach ($values as $value) {
      $builder->addLinef('%s,', $value_renderer->render($config, $value));
    }
    return $builder->closeContainer($this->container)->getCode();
  }
}
