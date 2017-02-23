---
docid: classes-codegen-shape
title: CodegenShape
layout: docs
permalink: /docs/classes/CodegenShape/
---

`CodegenShape` is primarily used with `CodegenType` to define type aliases for shapes;
it can also be used in other contexts by calling `->render()`.

It is constructed with an array of key names to types as strings:

``` php
<?hh

$factory->codegenShape(['foo' => 'string', 'bar' = 'int']);
```

Manual sections are also supported - to allow additional members to be added,
call `->setManualAttrID(string $key)` with a key that is unique within each file.
