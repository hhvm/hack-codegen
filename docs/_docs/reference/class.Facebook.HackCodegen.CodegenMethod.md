---
layout: docs
title: Facebook\HackCodegen\CodegenMethod
id: class.Facebook.HackCodegen.CodegenMethod
docid: class.Facebook.HackCodegen.CodegenMethod
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenMethod/
---
# Facebook\\HackCodegen\\CodegenMethod




Generate code for a method




Please don't use this class directly; instead use
the function codegen_method.  E.g.:




codegen_method('justDoIt')
->addParameter('int $x = 3')
->setReturnType('string')
->setBody('return (string) $x;')
->setProtected()
->render();




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenMethod extends CodegenMethodish {...}
```




### Public Methods




* [` ->setIsOverride(bool $value = true): \this `](<class.Facebook.HackCodegen.CodegenMethod.setIsOverride.md>)