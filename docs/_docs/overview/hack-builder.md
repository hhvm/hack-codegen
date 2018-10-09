---
docid: overview-hack-builder
title: Hack Builder
layout: docs
permalink: /docs/overview/hack-builder/
---

[`BaseCodeBuilder`](https://github.com/hhvm/hack-codegen/blob/master/src/BaseCodeBuilder.php)
provides basic features useful for generating code in most C-style programming langauges, such as
indentation, if blocks, and so on.
[`HackBuilder`](https://github.com/hhvm/hack-codegen/blob/master/src/HackBuilder.php) extends
`BaseCodeBuilder` with features of the Hack language.

When using Hack Codegen, `BaseCodeBuilder` is not usually used directly: `HackBuilder` is used to
generate function/method bodies and pseudo-main code, and higher-level APIs are used to create
classes, functions, etc.

The recommended way to get an instance of a `HackBuilder` is to first get an instance of a
`HackCodegenFactory`, then call `->codegenHackBuilder()` on it.

Fundamentals
------------

`$builder->add('some code');` is the lowest-level function: it appends the string you provide to
the generated code. On top of this, there is:

 - `->addLine('some code')`: also adds a newline
 - `->addLines($vector_of_lines)`: adds multiple lines

Many methods have a `sprintf`-style shortcut - eg:

 - `->addf('foo %s bar', $var)`
 - `->addLinef('foo %s bar', $var)`

As readable code is a goal of Hack Codegen, several whitespace helpers are provided:

 - `->ensureNewLine()`: add a newline if we're not currently at the start of a line
 - `->ensureEmptyLine()`: adds newlines as needed to have an empty line before any more code
 - `->indent()`: increase the number of spaces added to the start of any new non-empty lines
   (by default, this increases by 2 spaces - see `IHackCodegenConfig` to change this)
 - `->unindent()`: do the opposite
 - `->addLineWithSuggestedLineBreaks()`: any `\0` in the input string is replaced with a
   space if it will still fit in the desired maximum line length, otherwise a newline is
   added (and indented if needed)
 - `->addMultilineCall($call, $args)`: either renders the call all on one line, or with
   one argument on one line, depending on if it can fit within the maximum line length

Finally, you can get the generated code as a string by calling `->getCode()`.

Values
------

While the fundamentals can be combined with `var_export()` directly to generate code
for values, Hack Codegen provides an extensible system to simplify this; the simplest
interface to this is
`HackBuilder::addValue<T>(T $value, IHackBuilderValueRenderer<T> $formatter)`.

The two simplest renderers are:

 - `HackBuilderValues::export()` implements `IHackBuilderValueRenderer<mixed>`, so is able
   to render any value; it uses `var_export()`, but fixes up builtins to be instantiable,
   eg replacing `HH\Vector { }` with `Vector { }`
 - `HackBuilderValues::literal()` implements `IHackBuilderValueRenderer<string>`, and
   takes the value as literal code

More complicated renderers are available, including nested definitions for collections.
Additionally, shortcuts are provided for common uses:

 - `->addAssigment('$someVar', $value, $renderer)`: assigns `$value` to `$somevar`
 - `->addReturn('$somevar', $value, $renderer)`: return $value from the current function

`->addReturnf()` is also available, however it always treats the final string as literal code.

Blocks
------

Helpers are provided for common block constructs - eg:

``` php
<?hh
$builder
  ->startIf('$condition')
  ->addLine('doStuff();')
  ->endIf()
```

See [the reference documentation](/hack-codegen/docs/hack-builder/blocks/) for details.
