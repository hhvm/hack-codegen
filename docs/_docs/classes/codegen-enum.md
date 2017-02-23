---
docid: classes-codegen-enum
title: CodegenEnum
layout: docs
permalink: /docs/classes/CodegenEnum/
---

A `CodegenInterface` is created with `$factory->codegenEnum($name, $type)`; allowable
types are `string` or `int`.

Values are added to the enum with `->::addConst()` from `CodegenBaseClass`.

If you want an enum to be usable as its' base type, call `->setIsAs($base_type)`.
