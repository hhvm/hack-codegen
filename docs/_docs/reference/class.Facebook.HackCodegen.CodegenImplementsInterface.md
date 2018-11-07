---
layout: docs
title: Facebook\HackCodegen\CodegenImplementsInterface
id: class.Facebook.HackCodegen.CodegenImplementsInterface
docid: class.Facebook.HackCodegen.CodegenImplementsInterface
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenImplementsInterface/
---
# Facebook\\HackCodegen\\CodegenImplementsInterface




Describes an implemented interface, optionally including a comment, like:




// Generated from CowSchema::Moo()
IDoesMoo




Use the methods codegen_implements_interface[s] to instantiate it. E.g.:




$i = codegen_implements_interface('IUser')
->setComment('Some kind of user');
$class = codegen_class('MyClass')
->addInterface($i);




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenImplementsInterface implements ICodeBuilderRenderer {...}
```




### Public Methods




* [` ->__construct(IHackCodegenConfig $config, string $name) `](<class.Facebook.HackCodegen.CodegenImplementsInterface.__construct.md>)
* [` ->appendToBuilder(HackBuilder $builder): HackBuilder `](<class.Facebook.HackCodegen.CodegenImplementsInterface.appendToBuilder.md>)
* [` ->getName(): string `](<class.Facebook.HackCodegen.CodegenImplementsInterface.getName.md>)
* [` ->setComment(string $comment): \this `](<class.Facebook.HackCodegen.CodegenImplementsInterface.setComment.md>)
* [` ->setCommentf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): \this `](<class.Facebook.HackCodegen.CodegenImplementsInterface.setCommentf.md>)
* [` ->setGeneratedFrom(CodegenGeneratedFrom $from): \this `](<class.Facebook.HackCodegen.CodegenImplementsInterface.setGeneratedFrom.md>)