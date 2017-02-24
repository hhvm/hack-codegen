---
docid: hack-builder-custom-renderers
title: Custom Renderers
layout: docs
permalink: /docs/hack-builder/custom-renderers/
---

There are 3 main ways to implement custom rendering:

 - Pre-process your values to code, then use `HackBuilderValues::literal()` or 
   `HackBuilderKeys::literal()`
 - use `HackBuilderValues::lambda()` or `HackBuilderKeys::lambda()`
 - create classes that implement `IHackBuilderValueRenderer` or
   `IHackBuilderKeyRenderer`

Pre-processing
--------------

Pre-process the values so that the keys/values are the literal code you want
instead:

``` php
<?hh

$map = Map { /* ... */ };
$processed = Map { };
foreach ($map as $k => $v) {
  $processed[my_render_function($k)] = $v;
}
$builder
  ->addValue(
    $processed,
    HackBuilderValues::map(
      HackBuilderKeys::literal(), // The key is literal PHP code...
      HackBuilderValues::export(), // ... but use var_export() for the values
    ),
  );
```

This is usually the right approach for a single value, but not the most readable
for multiple values, eg for collections.

Lambdas
-------

The lambda takes an `IHackCodegenConfig` and the value; the value can be used
to create a `HackCodegenFactory` if neccessary, though as there's usually one
in scope, it's usually unneeded.

``` php
<?hh

$map = Map { /* ... */ };
$builder
  ->addValue(
    $map,
    HackBuilderValues::map(
      HackBuilderKeys::lambda(
        ($_config, $v) ==> my_render_function($v),
      ),
      HackBuilderValues::export(),
    ),
  );
```

This can be the right choice when the same renderer is needed for many values but
in few places.

Custom Renderer Classes
-----------------------

If you are using the same renderer in multiple places, you can implement the
`IHackBuilderKeysRenderer<T>` or `IHackBuilderValuesRenderer<T>` interfaces;
this is the same way the built-ins are implemented.

While it's possible to implement both in the same class, it's usually better
not to so that the hack typechecker has more visibility into argument order
mistakes for collections.
