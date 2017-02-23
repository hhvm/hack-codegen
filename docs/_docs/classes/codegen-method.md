---
docid: classes-codegen-method
title: CodegenMethod
layout: docs
permalink: /docs/classes/CodegenMethod/
---

Methods are created with `$factory->codegenMethod('methodName')`, and have some
additional features compared to `CodegenFunction`:

 - `->setIsOverride(bool $value = true)`: add the `<<__Override>>` attribute, which
   requires that the method is declared in a parent class
 - `->setIsFinal(bool $value = true)`: mark the method as final
 - `->setIsAbstract(bool $value = true)`: mark the method as abstract
 - `->setIsStatic(bool $value = true)`: mark the method as static
