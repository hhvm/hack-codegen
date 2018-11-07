---
layout: docs
title: codegenGeneratedFromScript
id: interface.Facebook.HackCodegen.ICodegenFactory.codegenGeneratedFromScript
docid: interface.Facebook.HackCodegen.ICodegenFactory.codegenGeneratedFromScript
permalink: /docs/reference/interface.Facebook.HackCodegen.ICodegenFactory.codegenGeneratedFromScript/
---
# Facebook\\HackCodegen\\ICodegenFactory::codegenGeneratedFromScript()




Generate a documentation comment indicating that a particular script was
used to generate a file




``` Hack
public function codegenGeneratedFromScript(
  ?string $script = NULL,
): Facebook\HackCodegen\CodegenGeneratedFrom;
```




## Parameters




* ` ?string $script = NULL ` the script used to generate the file. If `` null ``, it is
  inferred from the current process.




## Returns




- [` Facebook\HackCodegen\CodegenGeneratedFrom `](<class.Facebook.HackCodegen.CodegenGeneratedFrom.md>)