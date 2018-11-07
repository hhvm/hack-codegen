---
layout: docs
title: codegenEnumMemberf
id: interface.Facebook.HackCodegen.ICodegenFactory.codegenEnumMemberf
docid: interface.Facebook.HackCodegen.ICodegenFactory.codegenEnumMemberf
permalink: /docs/reference/interface.Facebook.HackCodegen.ICodegenFactory.codegenEnumMemberf/
---
# Facebook\\HackCodegen\\ICodegenFactory::codegenEnumMemberf()




Create an enum member using a %-placeholder format string for the constant
name




``` Hack
public function codegenEnumMemberf(
  HH\Lib\Str\SprintfFormatString $format,
  mixed ...$args,
): Facebook\HackCodegen\CodegenEnumMember;
```




## Parameters




+ ` HH\Lib\Str\SprintfFormatString $format `
+ ` mixed ...$args `




## Returns




* [` Facebook\HackCodegen\CodegenEnumMember `](<class.Facebook.HackCodegen.CodegenEnumMember.md>)