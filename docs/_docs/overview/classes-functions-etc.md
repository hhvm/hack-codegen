---
docid: overview-definitions
title: Classes, Functions, etc
layout: docs
permalink: /docs/overview/classes-functions-etc/
---

While it's possible to use `HackBuilder` directly to build up definitions of classes, functions,
and so on, helpers are provided and recommended; assuming you have a `HackCodegenFactory`, you can
create definitions with methods like:

 - `->codegenClass('SomeClass'): CodegenClass`
 - `->codegenTrait('SomeTrait'): CodegenTrait`
 - `->codegenMethod('someMethod'): CodegenMethod`
 - `->codegenShape($members)`: `CodegenShape`

Using these helpers is recommended; see
[`IHackCodegenFactory`](https://github.com/hhvm/hack-codegen/blob/master/src/ICodegenFactory.php)
for a complete list.
