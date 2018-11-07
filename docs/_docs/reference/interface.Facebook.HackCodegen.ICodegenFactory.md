---
layout: docs
title: Facebook\HackCodegen\ICodegenFactory
id: interface.Facebook.HackCodegen.ICodegenFactory
docid: interface.Facebook.HackCodegen.ICodegenFactory
permalink: /docs/reference/interface.Facebook.HackCodegen.ICodegenFactory/
---
# Facebook\\HackCodegen\\ICodegenFactory




Interface for factory methods to create codegen




This is the main entrypoint into the library; most users will want to use
the concrete ` HackCodegenFactory ` class.




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

interface ICodegenFactory {...}
```




### Public Methods




- [` ->codegenClass(string $name): CodegenClass `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenClass.md>)\
  Create a class

- [` ->codegenClassConstant(string $name): CodegenClassConstant `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenClassConstant.md>)\
  Create a class constant

- [` ->codegenClassConstantf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenClassConstant `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenClassConstantf.md>)\
  Create a class constant using a %-placeholder format string for the
  constant name

- [` ->codegenClassf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenClass `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenClassf.md>)\
  Create a class, using a %-placeholder format string for the class
  name

- [` ->codegenConstant(string $name): CodegenConstant `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenConstant.md>)\
  Create a top-level constant (not a class constant)

- [` ->codegenConstantf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenConstant `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenConstantf.md>)\
  Create a top-level constant (not a class constant), using a %-placeholder
  format string for the constant name

- [` ->codegenConstructor(): CodegenConstructor `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenConstructor.md>)

- [` ->codegenEnum(string $name, string $enumType): CodegenEnum `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenEnum.md>)

- [` ->codegenEnumMember(string $name): CodegenEnumMember `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenEnumMember.md>)\
  Create an enum member

- [` ->codegenEnumMemberf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenEnumMember `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenEnumMemberf.md>)\
  Create an enum member using a %-placeholder format string for the constant
  name

- [` ->codegenFile(string $file): CodegenFile `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenFile.md>)\
  Create a file with the specified filename/path

- [` ->codegenFilef(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenFile `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenFilef.md>)\
  Create a file with the specified filename/path using %-placeholder
  format strings

- [` ->codegenFunction(string $name): CodegenFunction `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenFunction.md>)\
  Create a top-level function (not a method)

- [` ->codegenFunctionf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenFunction `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenFunctionf.md>)\
  Create a top-level function (not a method), using a %-placeholder format
  string for the function name

- [` ->codegenGeneratedFromClass(string $class): CodegenGeneratedFrom `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenGeneratedFromClass.md>)\
  Generate a documentation comment indicating that a particular class was
  used to generate a file

- [` ->codegenGeneratedFromMethod(string $class, string $method): CodegenGeneratedFrom `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenGeneratedFromMethod.md>)\
  Generate a documentation comment indicating that a particular method was
  used to generate a file

- [` ->codegenGeneratedFromMethodWithKey(string $class, string $method, string $key): CodegenGeneratedFrom `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenGeneratedFromMethodWithKey.md>)\
  Generate a documentation comment indicating that a particular method was
  used to generate a file, with additional data

- [` ->codegenGeneratedFromScript(?string $script = NULL): CodegenGeneratedFrom `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenGeneratedFromScript.md>)\
  Generate a documentation comment indicating that a particular script was
  used to generate a file

- [` ->codegenHackBuilder(): HackBuilder `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenHackBuilder.md>)\
  Create a `` HackBuilder ``; this is used to generate code, rather than
  declarations

- [` ->codegenImplementsInterface(string $name): CodegenImplementsInterface `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenImplementsInterface.md>)\
  Generate a declaration that a class implements a specified interface

- [` ->codegenImplementsInterfaces(\ Traversable<string> $names): Traversable<CodegenImplementsInterface> `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenImplementsInterfaces.md>)\
  Generate declarations that a class implements the specified interfaces

- [` ->codegenInterface(string $name): CodegenInterface `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenInterface.md>)

- [` ->codegenMethod(string $name): CodegenMethod `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenMethod.md>)\
  Create a method

- [` ->codegenMethodf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenMethod `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenMethodf.md>)\
  Create a method, using a %-placeholder format string for the name

- [` ->codegenNewtype(string $name): CodegenType `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenNewtype.md>)\
  Generate a `` newtype `` declaration

- [` ->codegenProperty(string $name): CodegenProperty `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenProperty.md>)\
  Generate a class or trait property

- [` ->codegenPropertyf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): CodegenProperty `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenPropertyf.md>)\
  Generate a class or trait property, using a %-placehodler format string
  for the property name

- [` ->codegenShape(CodegenShapeMember ...$members): CodegenShape `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenShape.md>)

- [` ->codegenTrait(string $name): CodegenTrait `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenTrait.md>)

- [` ->codegenType(string $name): CodegenType `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenType.md>)\
  Generate a `` type `` declaration

- [` ->codegenTypeConstant(string $name): CodegenTypeConstant `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenTypeConstant.md>)\
  Create a class type constant

- [` ->codegenTypeConstantf(\HH\Lib\Str\SprintfFormatString $name, \mixed ...$args): CodegenTypeConstant `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenTypeConstantf.md>)\
  Create a class type constant using a %-placeholder format string for the
  name

- [` ->codegenUsesTrait(string $name): CodegenUsesTrait `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenUsesTrait.md>)\
  Generate a 'use' statement, for adding a trait into a class or another
  trait

- [` ->codegenUsesTraits(\ Traversable<string> $traits): Traversable<CodegenUsesTrait> `](<interface.Facebook.HackCodegen.ICodegenFactory.codegenUsesTraits.md>)\
  Generate a 'use' statements, for adding traits into a class or another
  trait
