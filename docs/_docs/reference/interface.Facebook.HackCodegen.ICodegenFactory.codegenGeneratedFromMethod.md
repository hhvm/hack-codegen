---
layout: docs
title: codegenGeneratedFromMethod
id: interface.Facebook.HackCodegen.ICodegenFactory.codegenGeneratedFromMethod
docid: interface.Facebook.HackCodegen.ICodegenFactory.codegenGeneratedFromMethod
permalink: /docs/reference/interface.Facebook.HackCodegen.ICodegenFactory.codegenGeneratedFromMethod/
---
# Facebook\\HackCodegen\\ICodegenFactory::codegenGeneratedFromMethod()




Generate a documentation comment indicating that a particular method was
used to generate a file




``` Hack
public function codegenGeneratedFromMethod(
  string $class,
  string $method,
): Facebook\HackCodegen\CodegenGeneratedFrom;
```




## Parameters




+ ` string $class `
+ ` string $method `




## Returns




* [` Facebook\HackCodegen\CodegenGeneratedFrom `](<class.Facebook.HackCodegen.CodegenGeneratedFrom.md>)