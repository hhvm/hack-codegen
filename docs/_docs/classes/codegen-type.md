---
docid: classes-codegen-type
title: CodegenType
layout: docs
permalink: /docs/classes/CodegenType/
---

Instances of `CodegenType` represent type aliases, and created either with
`$factory->codegenType(Name)` or `$factory->codegenNewType('Name')`, and the
base type is set by calling `->setType('SomeOtherType')`.

Types can be added directly to a `CodegenFile` via `$file->addBeforeType()` or
`$file->addAfterType()`; class type constants are set directly as strings.

Shapes
------

Instead of `->setType($string)`, `->setShape($codegen_shape)` can be used with
[`CodegenShape`](/hack-codegen/docs/classes/CodegenShape/) to define shapes in a
more structured manner.
