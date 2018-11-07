---
layout: docs
title: Facebook\HackCodegen\CodegenFunction
id: class.Facebook.HackCodegen.CodegenFunction
docid: class.Facebook.HackCodegen.CodegenFunction
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenFunction/
---
# Facebook\\HackCodegen\\CodegenFunction




Generate code for a function




Please don't use this class directly; instead
use the function codegen_function.  E.g.:




codegen_function('justDoIt')
->addParameter('int $x = 3')
->setReturnType('string')
->setBody('return (string) $x;')
->render();




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenFunction extends CodegenFunctionish {...}
```


