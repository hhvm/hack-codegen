---
layout: docs
title: Facebook\HackCodegen\CodegenEnum
id: class.Facebook.HackCodegen.CodegenEnum
docid: class.Facebook.HackCodegen.CodegenEnum
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenEnum/
---
# Facebook\\HackCodegen\\CodegenEnum




Generate code for an enum




```
$factory->codegenEnum('Foo', 'int')
 ->setIsAs('int')
 ->addConst('NAME', $value, 'Comment...')
 ->render();
```




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenEnum implements ICodeBuilderRenderer {...}
```




### Public Methods




* [` ->__construct(IHackCodegenConfig $config, string $name, string $enumType) `](<class.Facebook.HackCodegen.CodegenEnum.__construct.md>)\
  Create an instance
* [` ->addMember(CodegenEnumMember $member): \this `](<class.Facebook.HackCodegen.CodegenEnum.addMember.md>)
* [` ->addMembers(vec<CodegenEnumMember> $members): \this `](<class.Facebook.HackCodegen.CodegenEnum.addMembers.md>)
* [` ->appendToBuilder(HackBuilder $builder): HackBuilder `](<class.Facebook.HackCodegen.CodegenEnum.appendToBuilder.md>)
* [` ->getIsAs(): ?string `](<class.Facebook.HackCodegen.CodegenEnum.getIsAs.md>)
* [` ->setDocBlock(string $comment): \this `](<class.Facebook.HackCodegen.CodegenEnum.setDocBlock.md>)
* [` ->setHasManualFooter(bool $value = true, ?string $name = NULL, ?string $contents = NULL): \this `](<class.Facebook.HackCodegen.CodegenEnum.setHasManualFooter.md>)
* [` ->setHasManualHeader(bool $value = true, ?string $name = NULL, ?string $contents = NULL): \this `](<class.Facebook.HackCodegen.CodegenEnum.setHasManualHeader.md>)
* [` ->setIsAs(string $is_as): \this `](<class.Facebook.HackCodegen.CodegenEnum.setIsAs.md>)\
  Make the enum usable directly as the specified type