---
layout: docs
title: codegenFunctionf
id: interface.Facebook.HackCodegen.ICodegenFactory.codegenFunctionf
docid: interface.Facebook.HackCodegen.ICodegenFactory.codegenFunctionf
permalink: /docs/reference/interface.Facebook.HackCodegen.ICodegenFactory.codegenFunctionf/
---
# Facebook\\HackCodegen\\ICodegenFactory::codegenFunctionf()




Create a top-level function (not a method), using a %-placeholder format
string for the function name




``` Hack
public function codegenFunctionf(
  HH\Lib\Str\SprintfFormatString $format,
  mixed ...$args,
): Facebook\HackCodegen\CodegenFunction;
```




## Parameters




- ` HH\Lib\Str\SprintfFormatString $format `
- ` mixed ...$args `




## Returns




+ [` Facebook\HackCodegen\CodegenFunction `](<class.Facebook.HackCodegen.CodegenFunction.md>)