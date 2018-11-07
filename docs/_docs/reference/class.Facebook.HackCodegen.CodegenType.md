---
layout: docs
title: Facebook\HackCodegen\CodegenType
id: class.Facebook.HackCodegen.CodegenType
docid: class.Facebook.HackCodegen.CodegenType
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenType/
---
# Facebook\\HackCodegen\\CodegenType




Generate code for a type or newtype definition




Please don't use this class
directly; instead use the functions codegen_type or codegen_newtype. E.g.:




codegen_type('Point')
->setType('(int, int)');




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenType implements ICodeBuilderRenderer {...}
```




### Public Methods




* [` ->__construct(IHackCodegenConfig $config, string $name) `](<class.Facebook.HackCodegen.CodegenType.__construct.md>)
* [` ->appendToBuilder(HackBuilder $builder): HackBuilder `](<class.Facebook.HackCodegen.CodegenType.appendToBuilder.md>)
* [` ->newType(): \this `](<class.Facebook.HackCodegen.CodegenType.newType.md>)
* [` ->setShape(CodegenShape $codegen_shape): \this `](<class.Facebook.HackCodegen.CodegenType.setShape.md>)
* [` ->setType(string $type): \this `](<class.Facebook.HackCodegen.CodegenType.setType.md>)