---
layout: docs
title: Facebook\HackCodegen\CodegenProperty
id: class.Facebook.HackCodegen.CodegenProperty
docid: class.Facebook.HackCodegen.CodegenProperty
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenProperty/
---
# Facebook\\HackCodegen\\CodegenProperty




Generate code for a property variable




Please don't use this class directly;
instead use the function codegen_property.  E.g.:




codegen_property('foo')
->setProtected()
->setType('string')
->setInlineComment('Represent the foo of the bar")
->render();




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenProperty implements ICodeBuilderRenderer {...}
```




### Public Methods




* [` ->__construct(IHackCodegenConfig $config, string $name) `](<class.Facebook.HackCodegen.CodegenProperty.__construct.md>)
* [` ->appendToBuilder(HackBuilder $builder): HackBuilder `](<class.Facebook.HackCodegen.CodegenProperty.appendToBuilder.md>)
* [` ->getName(): string `](<class.Facebook.HackCodegen.CodegenProperty.getName.md>)
* [` ->getType(): ?string `](<class.Facebook.HackCodegen.CodegenProperty.getType.md>)
* [` ->getValue(): \mixed `](<class.Facebook.HackCodegen.CodegenProperty.getValue.md>)
* [` ->setInlineComment(string $comment): \this `](<class.Facebook.HackCodegen.CodegenProperty.setInlineComment.md>)
* [` ->setIsStatic(bool $value = true): \this `](<class.Facebook.HackCodegen.CodegenProperty.setIsStatic.md>)
* [` ->setType(string $type): \this `](<class.Facebook.HackCodegen.CodegenProperty.setType.md>)\
  Set the type of the member var
* [` ->setTypef(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): \this `](<class.Facebook.HackCodegen.CodegenProperty.setTypef.md>)
* [` ->setValue<\T>(\T $value, \ IHackBuilderValueRenderer<\T> $renderer): \this `](<class.Facebook.HackCodegen.CodegenProperty.setValue.md>)\
  Set the initial value for the variable