---
layout: docs
title: Facebook\HackCodegen\CodegenMethodish
id: class.Facebook.HackCodegen.CodegenMethodish
docid: class.Facebook.HackCodegen.CodegenMethodish
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenMethodish/
---
# Facebook\\HackCodegen\\CodegenMethodish




Base class to generate a method or a constructor




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

abstract class CodegenMethodish extends CodegenFunctionish implements ICodeBuilderRenderer {...}
```




### Public Methods




- [` ->appendToBuilder(HackBuilder $builder): HackBuilder `](<class.Facebook.HackCodegen.CodegenMethodish.appendToBuilder.md>)
- [` ->isAbstract(): bool `](<class.Facebook.HackCodegen.CodegenMethodish.isAbstract.md>)
- [` ->isStatic(): bool `](<class.Facebook.HackCodegen.CodegenMethodish.isStatic.md>)
- [` ->setContainingClass(CodegenClassish $class): \this `](<class.Facebook.HackCodegen.CodegenMethodish.setContainingClass.md>)
- [` ->setIsAbstract(bool $value = true): \this `](<class.Facebook.HackCodegen.CodegenMethodish.setIsAbstract.md>)
- [` ->setIsFinal(bool $value = true): \this `](<class.Facebook.HackCodegen.CodegenMethodish.setIsFinal.md>)
- [` ->setIsStatic(bool $value = true): \this `](<class.Facebook.HackCodegen.CodegenMethodish.setIsStatic.md>)







### Private Methods




+ [` ->getFunctionDeclaration(): string `](<class.Facebook.HackCodegen.CodegenMethodish.getFunctionDeclaration.md>)