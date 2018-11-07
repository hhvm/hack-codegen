---
layout: docs
title: codegenTypeConstantf
id: interface.Facebook.HackCodegen.ICodegenFactory.codegenTypeConstantf
docid: interface.Facebook.HackCodegen.ICodegenFactory.codegenTypeConstantf
permalink: /docs/reference/interface.Facebook.HackCodegen.ICodegenFactory.codegenTypeConstantf/
---
# Facebook\\HackCodegen\\ICodegenFactory::codegenTypeConstantf()




Create a class type constant using a %-placeholder format string for the
name




``` Hack
public function codegenTypeConstantf(
  HH\Lib\Str\SprintfFormatString $name,
  mixed ...$args,
): Facebook\HackCodegen\CodegenTypeConstant;
```




## Parameters




* ` HH\Lib\Str\SprintfFormatString $name `
* ` mixed ...$args `




## Returns




- [` Facebook\HackCodegen\CodegenTypeConstant `](<class.Facebook.HackCodegen.CodegenTypeConstant.md>)