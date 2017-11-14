---
docid: hack-builder-blocks
title: Blocks
layout: docs
permalink: /docs/hack-builder/blocks/
---

All forms of blocks can be created with the fundamental constructs - eg an `if`
block can be created with:

``` php
<?hh
$builder
  ->addLine('if ($condition) {')
  ->indent()
  /* do other stuff */
  ->unindent()
  ->ensureNewLine()
  ->addLine('}');
```

This is verbose and hard to scan through; methods are provided that help with both
for common structures.

If Statements
-------------

``` php
<?hh
$builder
  ->startIfBlock($condition)
  /* do stuff */
  ->addElseIfBlock($condition)
  /* do other stuff */
  ->addElseBlock()
  /* do weird stuff */
  ->endIfBlock();
```

Switch Statements
-----------------

``` php
<?hh
$builder
  ->startSwitch('$var')
  ->addCase('foo', HackBuilderValues::export())
  /* do stuff */
  ->breakCase()
  ->addCase('bar', HackBuilderValues::export())
  /* do stuff */
  ->returnCase('$x', HackBuilderValues::literal())
  ->addDefault()
  ->breakCase()
  ->endSwitch();
```

For-Each Loops
--------------

``` php
<?hh
$builder
  ->startForeachLoop('$list', '$key', '$value')
  /* do stuff */
  ->endForeachLoop();
```

The second parameter can be null.

Try-Catch Blocks
----------------

``` php
<?hh
$builder
  ->startTryBlock()
  /* do stuff */
  ->addCatchBlock('SomeException', '$e')
  /* do stuff */
  ->addFinallyBlock()
  /* do stuff */
  ->endTryBlock();
```
