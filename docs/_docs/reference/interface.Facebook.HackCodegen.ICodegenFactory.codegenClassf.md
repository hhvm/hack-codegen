---
layout: docs
title: codegenClassf
id: interface.Facebook.HackCodegen.ICodegenFactory.codegenClassf
docid: interface.Facebook.HackCodegen.ICodegenFactory.codegenClassf
permalink: /docs/reference/interface.Facebook.HackCodegen.ICodegenFactory.codegenClassf/
---
# Facebook\\HackCodegen\\ICodegenFactory::codegenClassf()




Create a class, using a %-placeholder format string for the class
name




``` Hack
public function codegenClassf(
  HH\Lib\Str\SprintfFormatString $format,
  mixed ...$args,
): Facebook\HackCodegen\CodegenClass;
```




## Parameters




* ` HH\Lib\Str\SprintfFormatString $format `
* ` mixed ...$args `




## Returns




- [` Facebook\HackCodegen\CodegenClass `](<class.Facebook.HackCodegen.CodegenClass.md>)