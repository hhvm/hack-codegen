---
layout: docs
title: Facebook\HackCodegen\CodegenClassish
id: class.Facebook.HackCodegen.CodegenClassish
docid: class.Facebook.HackCodegen.CodegenClassish
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenClassish/
---
# Facebook\\HackCodegen\\CodegenClassish




Abstract class to generate class-like definitions




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

abstract class CodegenClassish implements ICodeBuilderRenderer {...}
```




### Public Methods




- [` ->__construct(IHackCodegenConfig $config, string $name) `](<class.Facebook.HackCodegen.CodegenClassish.__construct.md>)
- [` ->addConstant(CodegenClassConstant $const): \this `](<class.Facebook.HackCodegen.CodegenClassish.addConstant.md>)
- [` ->addGeneric(string $decl): \this `](<class.Facebook.HackCodegen.CodegenClassish.addGeneric.md>)\
  Add a generic parameter
- [` ->addGenericSubtypeConstraint(string $subtype, string $baseType): \this `](<class.Facebook.HackCodegen.CodegenClassish.addGenericSubtypeConstraint.md>)\
  Add a generic parameter with subtype constraint
- [` ->addGenericSupertypeConstraint(string $superType, string $subtype): \this `](<class.Facebook.HackCodegen.CodegenClassish.addGenericSupertypeConstraint.md>)\
  Add a generic parameter with supertype constraint
- [` ->addGenericf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): \this `](<class.Facebook.HackCodegen.CodegenClassish.addGenericf.md>)\
  Add a generic parameter using a %-placeholder format string
- [` ->addGenerics(\ Traversable<string> $generics_decl): \this `](<class.Facebook.HackCodegen.CodegenClassish.addGenerics.md>)\
  Add generic parameters
- [` ->addMethod(CodegenMethod $method): \this `](<class.Facebook.HackCodegen.CodegenClassish.addMethod.md>)
- [` ->addMethods(Traversable<CodegenMethod> $methods): \this `](<class.Facebook.HackCodegen.CodegenClassish.addMethods.md>)
- [` ->addProperties(Traversable<CodegenProperty> $vars): \this `](<class.Facebook.HackCodegen.CodegenClassish.addProperties.md>)
- [` ->addProperty(CodegenProperty $var): \this `](<class.Facebook.HackCodegen.CodegenClassish.addProperty.md>)
- [` ->addTrait(CodegenUsesTrait $trait): \this `](<class.Facebook.HackCodegen.CodegenClassish.addTrait.md>)
- [` ->addTraits(Traversable<CodegenUsesTrait> $traits): \this `](<class.Facebook.HackCodegen.CodegenClassish.addTraits.md>)
- [` ->addTypeConstant(CodegenTypeConstant $const): \this `](<class.Facebook.HackCodegen.CodegenClassish.addTypeConstant.md>)
- [` ->appendToBuilder(HackBuilder $builder): HackBuilder `](<class.Facebook.HackCodegen.CodegenClassish.appendToBuilder.md>)\
  Append the entire declaration to a `` HackBuilder ``
- [` ->getName(): string `](<class.Facebook.HackCodegen.CodegenClassish.getName.md>)
- [` ->setDocBlock(string $comment): \this `](<class.Facebook.HackCodegen.CodegenClassish.setDocBlock.md>)
- [` ->setGeneratedFrom(CodegenGeneratedFrom $from): \this `](<class.Facebook.HackCodegen.CodegenClassish.setGeneratedFrom.md>)
- [` ->setHasManualDeclarations(bool $value = true, ?string $name = NULL, ?string $contents = NULL): \this `](<class.Facebook.HackCodegen.CodegenClassish.setHasManualDeclarations.md>)\
  If value is set to true, the class will have a section for manually adding
  declarations, such as type constants
- [` ->setHasManualMethodSection(bool $value = true, ?string $name = NULL, ?string $contents = NULL): \this `](<class.Facebook.HackCodegen.CodegenClassish.setHasManualMethodSection.md>)\
  Set whether or not the class has a section to contain manually written
  or modified methods
- [` ->setIsConsistentConstruct(bool $value = true): \this `](<class.Facebook.HackCodegen.CodegenClassish.setIsConsistentConstruct.md>)\
  Requires subclasses to have compatible constructors







### Protected Methods




+ [` ->appendBodyToBuilder(HackBuilder $builder): \void `](<class.Facebook.HackCodegen.CodegenClassish.appendBodyToBuilder.md>)\
  Append just the body of the class (between `` { `` and ``` } ``` to a
  ```` HackBuilder ````
+ [` ->buildConsts(HackBuilder $builder): \void `](<class.Facebook.HackCodegen.CodegenClassish.buildConsts.md>)
+ [` ->buildDeclaration(HackBuilder $builder): \void `](<class.Facebook.HackCodegen.CodegenClassish.buildDeclaration.md>)
+ [` ->buildGenericsDeclaration(): string `](<class.Facebook.HackCodegen.CodegenClassish.buildGenericsDeclaration.md>)
+ [` ->buildManualDeclarations(HackBuilder $builder): \void `](<class.Facebook.HackCodegen.CodegenClassish.buildManualDeclarations.md>)
+ [` ->buildMethods(HackBuilder $builder): \void `](<class.Facebook.HackCodegen.CodegenClassish.buildMethods.md>)
+ [` ->buildTraits(HackBuilder $builder): \void `](<class.Facebook.HackCodegen.CodegenClassish.buildTraits.md>)
+ [` ->buildVars(HackBuilder $builder): \void `](<class.Facebook.HackCodegen.CodegenClassish.buildVars.md>)
+ [` ->getExtraAttributes(): dict<string, vec<string>> `](<class.Facebook.HackCodegen.CodegenClassish.getExtraAttributes.md>)\
  Returns all the attributes defined on the class
+ [` ->getTraits(): vec<string> `](<class.Facebook.HackCodegen.CodegenClassish.getTraits.md>)