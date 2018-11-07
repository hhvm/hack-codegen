---
layout: docs
title: Facebook\HackCodegen\CodegenClass
id: class.Facebook.HackCodegen.CodegenClass
docid: class.Facebook.HackCodegen.CodegenClass
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenClass/
---
# Facebook\\HackCodegen\\CodegenClass




Generate code for a class




To construct an instance, use ` ICodegenFactory::codegenClass() `.




```
$factory->codegenClass('Foo')
 ->setExtends('bar')
 ->setIsFinal()
 ->addMethod(codegen_method('foobar'))
 ->render();
```




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenClass extends CodegenClassish {...}
```




### Public Methods




- [` ->addConstructorWrapperFunc(?Traversable<string> $params = NULL): \this `](<class.Facebook.HackCodegen.CodegenClass.addConstructorWrapperFunc.md>)\
  Add a factory function to wrap instantiations of to the class
- [` ->addDeclComment(string $comment): \this `](<class.Facebook.HackCodegen.CodegenClass.addDeclComment.md>)\
  Add a comment before the class
- [` ->getExtends(): ?string `](<class.Facebook.HackCodegen.CodegenClass.getExtends.md>)\
  Get the name of the parent class, or `` null `` if there is none
- [` ->setConstructor(CodegenConstructor $constructor): \this `](<class.Facebook.HackCodegen.CodegenClass.setConstructor.md>)
- [` ->setExtends(string $name): \this `](<class.Facebook.HackCodegen.CodegenClass.setExtends.md>)\
  Set the parent class of the generated class
- [` ->setExtendsf(\HH\Lib\Str\SprintfFormatString $name, \mixed ...$args): \this `](<class.Facebook.HackCodegen.CodegenClass.setExtendsf.md>)\
  Set the parent class of the generated class, using a %-placeholder format
  string
- [` ->setIsAbstract(bool $value = true): \this `](<class.Facebook.HackCodegen.CodegenClass.setIsAbstract.md>)
- [` ->setIsFinal(bool $value = true): \this `](<class.Facebook.HackCodegen.CodegenClass.setIsFinal.md>)







### Protected Methods




+ [` ->appendBodyToBuilder(HackBuilder $builder): \void `](<class.Facebook.HackCodegen.CodegenClass.appendBodyToBuilder.md>)
+ [` ->buildDeclaration(HackBuilder $builder): \void `](<class.Facebook.HackCodegen.CodegenClass.buildDeclaration.md>)







### Private Methods




* [` ->buildConstructor(HackBuilder $builder): \void `](<class.Facebook.HackCodegen.CodegenClass.buildConstructor.md>)