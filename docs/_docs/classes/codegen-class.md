---
docid: classes-codegen-class
title: CodegenClass
layout: docs
permalink: /docs/classes/CodegenClass/
---

A `CodegenClass` generates an actual class, and is created with
`$factory->codegenClass('ClassName')`. It extends
[`CodegenClassBase`](/hack-codegen/docs/classes/CodegenClassBase/), and adds
the following extra functionality:

 - `->setIsAbstract()`
 - `->setIsFinal()`
 - `->setExtends('Parent')`
 - `->setExtendsf('GenericParent<%s>', 'TFoo')`

Constructors
------------

`CodegenConstructor` is a subclass of
[`CodegenMethod`](/hack-codegen/docs/classes/CodegenMethod/) for constructors
only; while you can create a `CodegenMethod` called `__construct` and add it
with `->addMethod()`, `CodegenClass` has extra support for constructors:

 - `->setConstructor($constructor)`: set the constructor
 - `->addConstructorWrapperFunc()`: build a a factory function (not method) with the
   same name as the class, and the same parameters as the constructor.
