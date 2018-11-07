---
layout: docs
title: codegenPropertyf
id: interface.Facebook.HackCodegen.ICodegenFactory.codegenPropertyf
docid: interface.Facebook.HackCodegen.ICodegenFactory.codegenPropertyf
permalink: /docs/reference/interface.Facebook.HackCodegen.ICodegenFactory.codegenPropertyf/
---
# Facebook\\HackCodegen\\ICodegenFactory::codegenPropertyf()




Generate a class or trait property, using a %-placehodler format string
for the property name




``` Hack
public function codegenPropertyf(
  HH\Lib\Str\SprintfFormatString $format,
  mixed ...$args,
): Facebook\HackCodegen\CodegenProperty;
```




## Parameters




+ ` HH\Lib\Str\SprintfFormatString $format `
+ ` mixed ...$args `




## Returns




* [` Facebook\HackCodegen\CodegenProperty `](<class.Facebook.HackCodegen.CodegenProperty.md>)