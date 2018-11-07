---
layout: docs
title: Facebook\HackCodegen\CodegenConstructor
id: class.Facebook.HackCodegen.CodegenConstructor
docid: class.Facebook.HackCodegen.CodegenConstructor
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenConstructor/
---
# Facebook\\HackCodegen\\CodegenConstructor




Generate code for a constructor




```
$codegen_factory->codegenConstructor()
 ->setBody('$this->x = new Foo();')
 ->render();
```




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenConstructor extends CodegenMethodish {...}
```




### Public Methods




+ [` ->__construct(IHackCodegenConfig $config) `](<class.Facebook.HackCodegen.CodegenConstructor.__construct.md>)