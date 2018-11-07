---
layout: docs
title: Facebook\HackCodegen\CodegenGeneratedFrom
id: class.Facebook.HackCodegen.CodegenGeneratedFrom
docid: class.Facebook.HackCodegen.CodegenGeneratedFrom
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenGeneratedFrom/
---
# Facebook\\HackCodegen\\CodegenGeneratedFrom




Describes how the code was generated in order to write a comment on
the generated file




Use one of the helper functions below (codegen_generated_from_*). E.g.




$generated_from =  codegen_generated_from_script();
$file = codegen_file('file.php')
->setGeneratedFrom($generated_from);




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class CodegenGeneratedFrom implements ICodeBuilderRenderer {...}
```




### Public Methods




+ [` ->__construct(IHackCodegenConfig $config, string $msg) `](<class.Facebook.HackCodegen.CodegenGeneratedFrom.__construct.md>)
+ [` ->appendToBuilder(HackBuilder $builder): HackBuilder `](<class.Facebook.HackCodegen.CodegenGeneratedFrom.appendToBuilder.md>)