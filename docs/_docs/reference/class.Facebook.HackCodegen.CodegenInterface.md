---
layout: docs
title: Facebook\HackCodegen\CodegenInterface
id: class.Facebook.HackCodegen.CodegenInterface
docid: class.Facebook.HackCodegen.CodegenInterface
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenInterface/
---
# Facebook\\HackCodegen\\CodegenInterface




Generate code for an interface




Please don't use this class directly; instead
use the function codegen_interface.  E.g.:




codegen_interface('IFoo')
->addMethod(codegen_method('IBar'))




Notes:

- It can extend one or more other interfaces.
- It can have constants and methods.  You don't need to mark your methods as
  abstract; that will be done for you.
- Interfaces cannot use traits.




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenInterface extends CodegenClassish {...}
```




### Protected Methods




+ [` ->appendBodyToBuilder(HackBuilder $builder): \void `](<class.Facebook.HackCodegen.CodegenInterface.appendBodyToBuilder.md>)
+ [` ->buildDeclaration(HackBuilder $builder): \void `](<class.Facebook.HackCodegen.CodegenInterface.buildDeclaration.md>)