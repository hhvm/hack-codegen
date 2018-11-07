---
layout: docs
title: Facebook\HackCodegen\CodegenConstant
id: class.Facebook.HackCodegen.CodegenConstant
docid: class.Facebook.HackCodegen.CodegenConstant
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenConstant/
---
# Facebook\\HackCodegen\\CodegenConstant




Generate code for a constant that is not part of a class




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenConstant extends CodegenConstantish implements ICodeBuilderRenderer {...}
```




### Public Methods




* [` ->__construct(IHackCodegenConfig $config, string $name) `](<class.Facebook.HackCodegen.CodegenConstant.__construct.md>)
* [` ->appendToBuilder(HackBuilder $builder): HackBuilder `](<class.Facebook.HackCodegen.CodegenConstant.appendToBuilder.md>)
* [` ->getType(): ?string `](<class.Facebook.HackCodegen.CodegenConstant.getType.md>)
* [` ->setType(string $type): \this `](<class.Facebook.HackCodegen.CodegenConstant.setType.md>)
* [` ->setTypef(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): \this `](<class.Facebook.HackCodegen.CodegenConstant.setTypef.md>)\
  Set the type of the constant using a %-placeholder format string