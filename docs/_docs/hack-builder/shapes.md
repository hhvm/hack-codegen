---
docid: hack-builder-shapes
title: Shapes
layout: docs
permalink: /docs/hack-builder/shapes/
---

There are two built-in ways to render shapes, depending on if all the values have
the same type or not.

Shapes With Uniform Types
-------------------------

If all values in the shape have the same type, the rendering method only needs to
be specified once:

``` php
<?hh

$builder->addValue(
  shape(
    'foo' => 'bar',
    'herp' => 'derp',
  ),
  HackBuilderValues::shapeWithUniformRendering(
    HackBuilderValues::export(),
  ),
);
```

The type can be nested - eg:

``` php
<?hh

$builder->addValue(
  shape(
    'foo' => Vector { 'bar', 'baz' },
  ),
  HackBuilderValues::shapeWithUniformRendering(
    HackBuilderValues::vector(
      HackBuilderValues::export(),
    ),
  ),
);
```

Shapes With Varying Value Types
-------------------------------

A different renderer can be specified for each key:

``` php
<?hh
$builder->addValue(
  shape(
    'herp' => Vector { 'foo', 'bar' },
    'derp' => '$argv[0]',
  ),
  HackBuilderValues::shapeWithPerKeyRendering(
    shape(
      'herp' => HackBuilderValues::vector(
        HackBuilderValues::export(),
      ),
      'derp' => HackBuilderValues::literal(),
    ),
  ),
);
```
