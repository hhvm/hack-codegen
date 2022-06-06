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
  ContainerType,
  HackBuilder,
  IHackBuilderValueRenderer,
  IHackCodegenConfig,
};

final class HackBuilderNativeValueCollectionRenderer<Tv, T as Traversable<Tv>>
  implements IHackBuilderValueRenderer<T> {
  public function __construct(
    private ContainerType $container,
    private IHackBuilderValueRenderer<Tv> $valueRenderer,
  ) {
  }

  public function render(IHackCodegenConfig $config, T $values): string {
    $value_renderer = $this->valueRenderer;
    $builder = (new HackBuilder($config))->openContainer($this->container);
    foreach ($values as $value) {
      $builder->addLinef('%s,', $value_renderer->render($config, $value));
    }
    return $builder->closeContainer($this->container)->getCode();
  }
}
