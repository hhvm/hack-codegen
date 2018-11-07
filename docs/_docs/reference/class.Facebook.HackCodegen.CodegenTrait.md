---
layout: docs
title: Facebook\HackCodegen\CodegenTrait
id: class.Facebook.HackCodegen.CodegenTrait
docid: class.Facebook.HackCodegen.CodegenTrait
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenTrait/
---
# Facebook\\HackCodegen\\CodegenTrait




Generate code for a trait




Please don't use this class directly; instead use
the function codegen_trait.  E.g.:




codegen_trait('Foo')
->addMethod(codegen_method('foobar'))
->render();




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenTrait extends CodegenClassish {...}
```




### Public Methods




* [` ->addRequireClass(string $class): \this `](<class.Facebook.HackCodegen.CodegenTrait.addRequireClass.md>)
* [` ->addRequireInterface(string $interface): \this `](<class.Facebook.HackCodegen.CodegenTrait.addRequireInterface.md>)







### Protected Methods




- [` ->appendBodyToBuilder(HackBuilder $builder): \void `](<class.Facebook.HackCodegen.CodegenTrait.appendBodyToBuilder.md>)
- [` ->buildDeclaration(HackBuilder $builder): \void `](<class.Facebook.HackCodegen.CodegenTrait.buildDeclaration.md>)







### Private Methods




+ [` ->buildRequires(HackBuilder $builder): \void `](<class.Facebook.HackCodegen.CodegenTrait.buildRequires.md>)