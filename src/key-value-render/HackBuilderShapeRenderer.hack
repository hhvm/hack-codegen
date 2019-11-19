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
  HackBuilder,
  HackBuilderKeys,
  IHackBuilderValueRenderer,
  IHackCodegenConfig,
};

final class HackBuilderShapeRenderer
  implements IHackBuilderValueRenderer<shape(/* HH_FIXME[0003] */...)> {
  public function __construct(
    private shape(/* HH_FIXME[0003] */...) $valueRenderers
  ) {
  }

  public function render(
    IHackCodegenConfig $config,
    shape(/* HH_FIXME[0003] */...) $shape,
  ): string {
    $key_renderer = HackBuilderKeys::export();
    $value_renderers = Shapes::toArray($this->valueRenderers);
    $array = Shapes::toArray($shape);

    $builder = (new HackBuilder($config))->addLine('shape(')->indent();
    foreach ($array as $key => $value) {
      $value_renderer = idx($value_renderers, $key);
      invariant(
        $value_renderer !== null,
        'No renderer specified for key "%s"',
        $key,
      );
      invariant(
        $value_renderer is IHackBuilderValueRenderer<_>,
        'Value renderer for key "%s" is of type "%s", which is not a %s',
        $key,
        \is_object($value_renderer)
          ? \get_class($value_renderer)
          : \gettype($value_renderer),
        IHackBuilderValueRenderer::class,
      );
      $builder->addWithSuggestedLineBreaksf(
        "%s =>\0%s,\n",
        $key_renderer->render($config, $key),
        $value_renderer->render($config, /* HH_IGNORE_ERROR[4110] */ $value),
      );
    }
    return $builder->unindent()->add(')')->getCode();
  }
}
