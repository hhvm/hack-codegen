---
docid: classes-codegen-trait
title: CodegenTrait
layout: docs
permalink: /docs/classes/CodegenTrait/
---

A `CodegenTrait` is created with `$factory->codegenTrait(name)`; it can
be marked as implementing interfaces with
`->addInterface($codegen_implements_interface)`, and also adds two Hack-specific
features on top of `CodegenBaseClass`:

 - `->addRequireClass(string $class)`: only allow the trait to be used by `$class`
   or subclasses of `$class`.
 - `->addRequireInterface(string $class)`: only allow the trait to be used classes
   that implement the specified interface.
