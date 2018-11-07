---
layout: docs
title: Facebook\HackCodegen\CodegenUsesTrait
id: class.Facebook.HackCodegen.CodegenUsesTrait
docid: class.Facebook.HackCodegen.CodegenUsesTrait
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenUsesTrait/
---
# Facebook\\HackCodegen\\CodegenUsesTrait




Describe an used trait, optionally including a comment, like:




// Generated from CowSchema::Moo()
use MooInterface;




Use the methods codegen_uses_trait[s] to instantiate it. E.g.:




$trait = codegen_uses_trait('TFoo')
->setComment('Some common foo methods');
$class = codegen_class('MyClass')
->addTrait($trait);




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenUsesTrait {...}
```




### Public Methods




+ [` ->__construct(IHackCodegenConfig $config, string $name) `](<class.Facebook.HackCodegen.CodegenUsesTrait.__construct.md>)
+ [` ->getName(): string `](<class.Facebook.HackCodegen.CodegenUsesTrait.getName.md>)
+ [` ->render(): string `](<class.Facebook.HackCodegen.CodegenUsesTrait.render.md>)
+ [` ->setComment(string $comment): \this `](<class.Facebook.HackCodegen.CodegenUsesTrait.setComment.md>)
+ [` ->setCommentf(\HH\Lib\Str\SprintfFormatString $format, \mixed ...$args): \this `](<class.Facebook.HackCodegen.CodegenUsesTrait.setCommentf.md>)
+ [` ->setGeneratedFrom(CodegenGeneratedFrom $from): \this `](<class.Facebook.HackCodegen.CodegenUsesTrait.setGeneratedFrom.md>)