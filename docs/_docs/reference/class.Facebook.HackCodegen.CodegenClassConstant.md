---
layout: docs
title: Facebook\HackCodegen\CodegenClassConstant
id: class.Facebook.HackCodegen.CodegenClassConstant
docid: class.Facebook.HackCodegen.CodegenClassConstant
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenClassConstant/
---
# Facebook\\HackCodegen\\CodegenClassConstant




Generate code for a class constant




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenClassConstant extends CodegenConstantish {...}
```




### Public Methods




- [` ->__construct(IHackCodegenConfig $config, string $name) `](<class.Facebook.HackCodegen.CodegenClassConstant.__construct.md>)
- [` ->appendToBuilder(HackBuilder $builder): HackBuilder `](<class.Facebook.HackCodegen.CodegenClassConstant.appendToBuilder.md>)
- [` ->getType(): ?string `](<class.Facebook.HackCodegen.CodegenClassConstant.getType.md>)
- [` ->isAbstract(): bool `](<class.Facebook.HackCodegen.CodegenClassConstant.isAbstract.md>)
- [` ->setIsAbstract(bool $value): \this `](<class.Facebook.HackCodegen.CodegenClassConstant.setIsAbstract.md>)
- [` ->setType(string $type): \this `](<class.Facebook.HackCodegen.CodegenClassConstant.setType.md>)
- [` ->setTypef(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): \this `](<class.Facebook.HackCodegen.CodegenClassConstant.setTypef.md>)\
  Set the type of the constant using a %-placeholder format string