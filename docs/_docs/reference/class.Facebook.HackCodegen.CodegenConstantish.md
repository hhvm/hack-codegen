---
layout: docs
title: Facebook\HackCodegen\CodegenConstantish
id: class.Facebook.HackCodegen.CodegenConstantish
docid: class.Facebook.HackCodegen.CodegenConstantish
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenConstantish/
---
# Facebook\\HackCodegen\\CodegenConstantish




Generate code for a constant that is not part of a class




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

abstract class CodegenConstantish implements ICodeBuilderRenderer {...}
```




### Public Methods




- [` ->__construct(IHackCodegenConfig $config, string $name) `](<class.Facebook.HackCodegen.CodegenConstantish.__construct.md>)
- [` ->getDocBlock(): ?string `](<class.Facebook.HackCodegen.CodegenConstantish.getDocBlock.md>)
- [` ->getName(): string `](<class.Facebook.HackCodegen.CodegenConstantish.getName.md>)
- [` ->getValue(): ?string `](<class.Facebook.HackCodegen.CodegenConstantish.getValue.md>)\
  Returns the value as code
- [` ->setDocBlock(string $comment): \this `](<class.Facebook.HackCodegen.CodegenConstantish.setDocBlock.md>)
- [` ->setValue<\T>(\T $value, \ IHackBuilderValueRenderer<\T> $renderer): \this `](<class.Facebook.HackCodegen.CodegenConstantish.setValue.md>)\
  Set the value of the constant using a renderer