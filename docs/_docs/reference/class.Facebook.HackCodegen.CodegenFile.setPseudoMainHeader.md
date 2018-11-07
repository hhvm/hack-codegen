
***

layout: docs
title: setPseudoMainHeader
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenFile.setPseudoMainHeader.md
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