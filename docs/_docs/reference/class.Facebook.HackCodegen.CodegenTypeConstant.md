---
layout: docs
title: Facebook\HackCodegen\CodegenTypeConstant
id: class.Facebook.HackCodegen.CodegenTypeConstant
docid: class.Facebook.HackCodegen.CodegenTypeConstant
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenTypeConstant/
---
# Facebook\\HackCodegen\\CodegenTypeConstant




Generate code for a class type constant




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenTypeConstant extends CodegenConstantish implements ICodeBuilderRenderer {...}
```




### Public Methods




- [` ->__construct(IHackCodegenConfig $config, string $name) `](<class.Facebook.HackCodegen.CodegenTypeConstant.__construct.md>)
- [` ->appendToBuilder(HackBuilder $builder): HackBuilder `](<class.Facebook.HackCodegen.CodegenTypeConstant.appendToBuilder.md>)
- [` ->getConstraint(): ?string `](<class.Facebook.HackCodegen.CodegenTypeConstant.getConstraint.md>)
- [` ->isAbstract(): bool `](<class.Facebook.HackCodegen.CodegenTypeConstant.isAbstract.md>)
- [` ->setConstraint(string $c): \this `](<class.Facebook.HackCodegen.CodegenTypeConstant.setConstraint.md>)
- [` ->setConstraintf(\HH\Lib\Str\SprintfFormatString $c, \mixed ...$args): \this `](<class.Facebook.HackCodegen.CodegenTypeConstant.setConstraintf.md>)
- [` ->setIsAbstract(bool $value): \this `](<class.Facebook.HackCodegen.CodegenTypeConstant.setIsAbstract.md>)