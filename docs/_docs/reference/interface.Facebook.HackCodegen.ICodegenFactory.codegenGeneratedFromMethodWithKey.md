---
layout: docs
title: codegenGeneratedFromMethodWithKey
id: interface.Facebook.HackCodegen.ICodegenFactory.codegenGeneratedFromMethodWithKey
docid: interface.Facebook.HackCodegen.ICodegenFactory.codegenGeneratedFromMethodWithKey
permalink: /docs/reference/interface.Facebook.HackCodegen.ICodegenFactory.codegenGeneratedFromMethodWithKey/
---
# Facebook\\HackCodegen\\ICodegenFactory::codegenGeneratedFromMethodWithKey()




Generate a documentation comment indicating that a particular method was
used to generate a file, with additional data







``` Hack
public function codegenGeneratedFromMethodWithKey(
  string $class,
  string $method,
  string $key,
): Facebook\HackCodegen\CodegenGeneratedFrom;
```




## Parameters




- ` string $class `
- ` string $method `
- ` string $key `




## Returns




+ [` Facebook\HackCodegen\CodegenGeneratedFrom `](<class.Facebook.HackCodegen.CodegenGeneratedFrom.md>)