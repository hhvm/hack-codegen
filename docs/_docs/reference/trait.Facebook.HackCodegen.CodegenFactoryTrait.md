---
layout: docs
title: Facebook\HackCodegen\CodegenFactoryTrait
id: trait.Facebook.HackCodegen.CodegenFactoryTrait
docid: trait.Facebook.HackCodegen.CodegenFactoryTrait
permalink: /docs/reference/trait.Facebook.HackCodegen.CodegenFactoryTrait/
---
# Facebook\\HackCodegen\\CodegenFactoryTrait




Trait to implement ` ICodegenFactory ` if no special behavior is provided




You must implement [` getConfig() `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.getConfig.md>), but all other methods are final.




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

trait CodegenFactoryTrait implements ICodegenFactory {...}
```




### Public Methods




+ [` ->codegenClass(string $name): CodegenClass `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenClass.md>)
+ [` ->codegenClassConstant(string $name): CodegenClassConstant `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenClassConstant.md>)
+ [` ->codegenClassConstantf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenClassConstant `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenClassConstantf.md>)
+ [` ->codegenClassf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenClass `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenClassf.md>)
+ [` ->codegenConstant(string $name): CodegenConstant `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenConstant.md>)
+ [` ->codegenConstantf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenConstant `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenConstantf.md>)
+ [` ->codegenConstructor(): CodegenConstructor `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenConstructor.md>)
+ [` ->codegenEnum(string $name, string $enumType): CodegenEnum `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenEnum.md>)
+ [` ->codegenEnumMember(string $name): CodegenEnumMember `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenEnumMember.md>)
+ [` ->codegenEnumMemberf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenEnumMember `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenEnumMemberf.md>)
+ [` ->codegenFile(string $file): CodegenFile `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenFile.md>)
+ [` ->codegenFilef(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenFile `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenFilef.md>)
+ [` ->codegenFunction(string $name): CodegenFunction `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenFunction.md>)
+ [` ->codegenFunctionf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenFunction `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenFunctionf.md>)
+ [` ->codegenGeneratedFromClass(string $class): CodegenGeneratedFrom `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenGeneratedFromClass.md>)
+ [` ->codegenGeneratedFromMethod(string $class, string $method): CodegenGeneratedFrom `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenGeneratedFromMethod.md>)
+ [` ->codegenGeneratedFromMethodWithKey(string $class, string $method, string $key): CodegenGeneratedFrom `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenGeneratedFromMethodWithKey.md>)
+ [` ->codegenGeneratedFromScript(?string $script = NULL): CodegenGeneratedFrom `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenGeneratedFromScript.md>)
+ [` ->codegenHackBuilder(): HackBuilder `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenHackBuilder.md>)
+ [` ->codegenImplementsInterface(string $name): CodegenImplementsInterface `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenImplementsInterface.md>)
+ [` ->codegenImplementsInterfaces(\ Traversable<string> $names): vec<CodegenImplementsInterface> `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenImplementsInterfaces.md>)
+ [` ->codegenInterface(string $name): CodegenInterface `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenInterface.md>)
+ [` ->codegenMethod(string $name): CodegenMethod `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenMethod.md>)
+ [` ->codegenMethodf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenMethod `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenMethodf.md>)
+ [` ->codegenNewtype(string $name): CodegenType `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenNewtype.md>)
+ [` ->codegenProperty(string $name): CodegenProperty `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenProperty.md>)
+ [` ->codegenPropertyf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenProperty `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenPropertyf.md>)
+ [` ->codegenShape(CodegenShapeMember ...$members): CodegenShape `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenShape.md>)
+ [` ->codegenTrait(string $name): CodegenTrait `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenTrait.md>)
+ [` ->codegenType(string $name): CodegenType `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenType.md>)
+ [` ->codegenTypeConstant(string $name): CodegenTypeConstant `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenTypeConstant.md>)
+ [` ->codegenTypeConstantf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenTypeConstant `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenTypeConstantf.md>)
+ [` ->codegenUsesTrait(string $name): CodegenUsesTrait `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenUsesTrait.md>)
+ [` ->codegenUsesTraits(\ Traversable<string> $names): vec<CodegenUsesTrait> `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.codegenUsesTraits.md>)
+ [` ->getConfig(): IHackCodegenConfig `](<trait.Facebook.HackCodegen.CodegenFactoryTrait.getConfig.md>)