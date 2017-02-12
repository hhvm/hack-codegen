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

final class HackBuilderShapeRenderer
implements IHackBuilderValueRenderer<shape()> {
  public function __construct(
    private shape() $valueRenderers,
  ) {
  }

  final public function render(
    IHackCodegenConfig $config,
    shape() $shape,
  ): string {
    $key_renderer = HackBuilderKeys::export();
    $value_renderers = Shapes::toArray($this->valueRenderers);
    $array = Shapes::toArray($shape);

    $builder = (new HackBuilder($config))
      ->addLine('shape(')
      ->indent();
    foreach ($array as $key => $value) {
      $value_renderer = idx(
        $value_renderers,
        $key,
      );
      invariant(
        $value_renderer !== null,
        'No renderer specified for key "%s"',
        $key,
      );
      invariant(
        $value_renderer instanceof IHackBuilderValueRenderer,
        'Value renderer for key "%s" is of type "%s", which is not a %s',
        $key,
        is_object($value_renderer)
          ? get_class($value_renderer)
          : gettype($value_renderer),
        IHackBuilderValueRenderer::class,
      );
      $builder->addWithSuggestedLineBreaksf(
        "%s =>\t%s,\n",
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
