---
layout: docs
title: codegenMethodf
id: interface.Facebook.HackCodegen.ICodegenFactory.codegenMethodf
docid: interface.Facebook.HackCodegen.ICodegenFactory.codegenMethodf
permalink: /docs/reference/interface.Facebook.HackCodegen.ICodegenFactory.codegenMethodf/
---
# Facebook\\HackCodegen\\ICodegenFactory::codegenMethodf()




Create a method, using a %-placeholder format string for the name




``` Hack
public function codegenMethodf(
  HH\Lib\Str\SprintfFormatString $format,
  mixed ...$args,
): Facebook\HackCodegen\CodegenMethod;
```




## Parameters




+ ` HH\Lib\Str\SprintfFormatString $format `
+ ` mixed ...$args `




## Returns




* [` Facebook\HackCodegen\CodegenMethod `](<class.Facebook.HackCodegen.CodegenMethod.md>)