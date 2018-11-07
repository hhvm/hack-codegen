---
layout: docs
title: setPseudoMainHeader
id: class.Facebook.HackCodegen.CodegenFile.setPseudoMainHeader
docid: class.Facebook.HackCodegen.CodegenFile.setPseudoMainHeader
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenFile.setPseudoMainHeader/
---
# Facebook\\HackCodegen\\CodegenFile::setPseudoMainHeader()




Use to execute code before declarations




``` Hack
public function setPseudoMainHeader(
  string $code,
): this;
```




Useful for scripts; eg:
setPseudoMainHeader('require_once("vendor/autoload.php");');




## Parameters




* ` string $code `




## Returns




- ` this `