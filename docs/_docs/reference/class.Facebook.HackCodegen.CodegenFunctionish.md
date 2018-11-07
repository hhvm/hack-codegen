---
layout: docs
title: Facebook\HackCodegen\CodegenFunctionish
id: class.Facebook.HackCodegen.CodegenFunctionish
docid: class.Facebook.HackCodegen.CodegenFunctionish
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenFunctionish/
---
# Facebook\\HackCodegen\\CodegenFunctionish




Base class to generate a function or a method




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

abstract class CodegenFunctionish implements ICodeBuilderRenderer {...}
```




### Public Methods




+ [` ->__construct(IHackCodegenConfig $config, string $name) `](<class.Facebook.HackCodegen.CodegenFunctionish.__construct.md>)
+ [` ->addHHFixMe(int $code, string $why): \this `](<class.Facebook.HackCodegen.CodegenFunctionish.addHHFixMe.md>)
+ [` ->addParameter(string $param): \this `](<class.Facebook.HackCodegen.CodegenFunctionish.addParameter.md>)
+ [` ->addParameterf(\HH\Lib\Str\SprintfFormatString $param, \mixed ...$args): \this `](<class.Facebook.HackCodegen.CodegenFunctionish.addParameterf.md>)
+ [` ->addParameters(Traversable<string> $params): \this `](<class.Facebook.HackCodegen.CodegenFunctionish.addParameters.md>)
+ [` ->appendToBuilder(HackBuilder $builder): HackBuilder `](<class.Facebook.HackCodegen.CodegenFunctionish.appendToBuilder.md>)
+ [` ->getName(): string `](<class.Facebook.HackCodegen.CodegenFunctionish.getName.md>)
+ [` ->getParameters(): vec<string> `](<class.Facebook.HackCodegen.CodegenFunctionish.getParameters.md>)
+ [` ->getReturnType(): ?string `](<class.Facebook.HackCodegen.CodegenFunctionish.getReturnType.md>)
+ [` ->isManualBody(): bool `](<class.Facebook.HackCodegen.CodegenFunctionish.isManualBody.md>)
+ [` ->setBody(string $body): \this `](<class.Facebook.HackCodegen.CodegenFunctionish.setBody.md>)
+ [` ->setBodyf(\HH\Lib\Str\SprintfFormatString $body, \mixed ...$args): \this `](<class.Facebook.HackCodegen.CodegenFunctionish.setBodyf.md>)
+ [` ->setDocBlock(string $comment): \this `](<class.Facebook.HackCodegen.CodegenFunctionish.setDocBlock.md>)
+ [` ->setGeneratedFrom(CodegenGeneratedFrom $from): \this `](<class.Facebook.HackCodegen.CodegenFunctionish.setGeneratedFrom.md>)
+ [` ->setIsAsync(bool $value = true): \this `](<class.Facebook.HackCodegen.CodegenFunctionish.setIsAsync.md>)
+ [` ->setIsMemoized(bool $value = true): \this `](<class.Facebook.HackCodegen.CodegenFunctionish.setIsMemoized.md>)
+ [` ->setManualBody(bool $val = true): \this `](<class.Facebook.HackCodegen.CodegenFunctionish.setManualBody.md>)
+ [` ->setName(string $name): \this `](<class.Facebook.HackCodegen.CodegenFunctionish.setName.md>)
+ [` ->setReturnType(string $type): \this `](<class.Facebook.HackCodegen.CodegenFunctionish.setReturnType.md>)
+ [` ->setReturnTypef(\HH\Lib\Str\SprintfFormatString $type, \mixed ...$args): \this `](<class.Facebook.HackCodegen.CodegenFunctionish.setReturnTypef.md>)







### Protected Methods




* [` ->appendToBuilderBase(HackBuilder $builder, string $func_declaration, bool $is_abstract = false, string $containing_class_name = ''): HackBuilder `](<class.Facebook.HackCodegen.CodegenFunctionish.appendToBuilderBase.md>)\
  $is_abstract and $containing_class_name
  only valid for CodegenMethodX for code reuse purposes
* [` ->getExtraAttributes(): dict<string, vec<string>> `](<class.Facebook.HackCodegen.CodegenFunctionish.getExtraAttributes.md>)
* [` ->getFunctionDeclarationBase(string $keywords, bool $is_abstract = false): string `](<class.Facebook.HackCodegen.CodegenFunctionish.getFunctionDeclarationBase.md>)\
  Break lines for function declaration
* [` ->getMaxCodeLength(): int `](<class.Facebook.HackCodegen.CodegenFunctionish.getMaxCodeLength.md>)







### Private Methods




- [` ->getFunctionDeclaration(): string `](<class.Facebook.HackCodegen.CodegenFunctionish.getFunctionDeclaration.md>)