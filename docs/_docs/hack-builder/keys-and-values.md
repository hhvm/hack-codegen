---
docid: hack-builder-keys-and-values
title: Keys & Values
layout: docs
permalink: /docs/hack-builder/keys-and-values/
---

Hack Codegen has an extensible system for rendering values, used by:

 - `$hack_builder->addValue()`
 - `$hack_builder->addAssignment()`
 - `$hack_builder->addReturn()`
 - `$hack_builder->addReturnCase()`

This is based on the `IHackBuilderValueRenderer<T>` interface, which provides
`public function render(IHackCodegenConfig $config, T $input): string` which
returns PHP code.

Basic Built-Ins
---------------

Hack Codegen provides several implementation of this interface, accessed via
the [`HackBuilderValues`](https://github.com/hhvm/hack-codegen/blob/master/src/key-value-render/HackBuilderValues.php)
class:

 - `HackBuilderValues::literal()`: returns the $input as literal code
 - `HackBuilderValues::export()`: returns code that recreates `$input`; this is
   similar to `var_export()`
 - `HackBuilderValues::classname()`: renders the input as a `classname<T>`

For example:

``` php
<?hh
// Output: $foo = $bar;
$builder->addAssignment(
  '$foo',
  '$bar',
  HackBuilderValues::literal(),
);

// Output: $foo = '$bar';
$builder->addAssignment(
  '$foo',
  '$bar',
  HackBuilderValues::export(),
);

// Output: $foo = \Foo\Bar::class;
use Foo\Bar;
$builder->addAssignment(
  '$foo',
  Bar::class,
  HackBuilderValues::classname(),
);
```

Collection Built-Ins
--------------------

As collections can be nested, the renderers are nested; the built-in value container
renderers are:

 - `HackBuilderValues::valueArray($value_renderer)`
 - `HackBuilderValues::vector($value_renderer)`
 - `HackBuilderValues::immVector($value_renderer)`
 - `HackBuilderValues::set($value_renderer)`
 - `HackBuilderValues::immSet($value_renderer)`

For example:
``` php
<?hh

$builder
  ->addValue(
    Vector { 'foo' },
    HackBuilderValues::vector(
      HackBuilderValues::export(),
    ),
  )
  ->addValue(
    Vector { Vector { 'foo ' } },
    HackBuilderValues::vector(
      HackBuilderValues::vector(
        HackBuilderValues::export(),
      )
    ),
  );
```

`IHackBuilderKeyRenderer<T>` is similar to `IHackBuilderValueRenderer<T>`, however
it requires that `T` is an `arraykey` (`string` or `int`); this is used by `Map`,
`Set`, key-value arrays, and so on - key renderers are required to implement this
interface.

The built-in key-value container renderers are:

 - `HackBuilderValues::keyValueArray($key_renderer, $value_renderer)`
 - `HackBuilderValues::map($key_renderer, $value_renderer)`
 - `HackBuilderValues::immMap($key_renderer, $value_renderer)`
