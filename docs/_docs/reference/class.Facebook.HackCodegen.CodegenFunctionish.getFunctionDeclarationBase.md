---
layout: docs
title: getFunctionDeclarationBase
id: class.Facebook.HackCodegen.CodegenFunctionish.getFunctionDeclarationBase
docid: class.Facebook.HackCodegen.CodegenFunctionish.getFunctionDeclarationBase
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenFunctionish.getFunctionDeclarationBase/
---
# Facebook\\HackCodegen\\CodegenFunctionish::getFunctionDeclarationBase()




Break lines for function declaration




``` Hack
protected function getFunctionDeclarationBase(
  string $keywords,
  bool $is_abstract = false,
): string;
```




First calculate the string length as
if there were no line break. If the string exceeds one line, try break
by having each parameter per line.




$is_abstract - only valid for CodegenMethodX for code reuse purposes




## Parameters




+ ` string $keywords `
+ ` bool $is_abstract = false `




## Returns




* ` string `