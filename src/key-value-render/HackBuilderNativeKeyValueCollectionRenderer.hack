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
  IHackBuilderKeyRenderer,
  IHackBuilderValueRenderer,
  IHackCodegenConfig,
};

final class HackBuilderNativeKeyValueCollectionRenderer<
  Tk as arraykey,
  Tv,
  T as KeyedTraversable<Tk, Tv>,
> implements IHackBuilderValueRenderer<T> {
  public function __construct(
    private ContainerType $container,
    private IHackBuilderKeyRenderer<Tk> $keyRenderer,
    private IHackBuilderValueRenderer<Tv> $valueRenderer,
  ) {
  }

  public function render(IHackCodegenConfig $config, T $values): string {
    $key_renderer = $this->keyRenderer;
    $value_renderer = $this->valueRenderer;

    $builder = (new HackBuilder($config))->openContainer($this->container);
    foreach ($values as $key => $value) {
      $builder->addWithSuggestedLineBreaksf(
        "%s =>\0%s,\n",
        $key_renderer->render($config, $key),
        $value_renderer->render($config, $value),
      );
    }
    return $builder->closeContainer($this->container)->getCode();
  }
}
