---
layout: docs
title: codegenFilef
id: interface.Facebook.HackCodegen.ICodegenFactory.codegenFilef
docid: interface.Facebook.HackCodegen.ICodegenFactory.codegenFilef
permalink: /docs/reference/interface.Facebook.HackCodegen.ICodegenFactory.codegenFilef/
---
# Facebook\\HackCodegen\\ICodegenFactory::codegenFilef()




Create a file with the specified filename/path using %-placeholder
format strings




``` Hack
public function codegenFilef(
  HH\Lib\Str\SprintfFormatString $format,
  mixed ...$args,
): Facebook\HackCodegen\CodegenFile;
```




## Parameters




* ` HH\Lib\Str\SprintfFormatString $format `
* ` mixed ...$args `




## Returns




- [` Facebook\HackCodegen\CodegenFile `](<class.Facebook.HackCodegen.CodegenFile.md>)