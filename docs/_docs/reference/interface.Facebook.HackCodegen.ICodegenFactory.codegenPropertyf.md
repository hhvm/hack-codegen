
***

layout: docs
title: codegenPropertyf
permalink: /docs/reference/interface.Facebook.HackCodegen.ICodegenFactory.codegenPropertyf.md
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