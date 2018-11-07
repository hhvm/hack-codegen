---
layout: docs
title: setPseudoMainFooter
id: class.Facebook.HackCodegen.CodegenFile.setPseudoMainFooter
docid: class.Facebook.HackCodegen.CodegenFile.setPseudoMainFooter
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenFile.setPseudoMainFooter/
---
# Facebook\\HackCodegen\\CodegenFile::setPseudoMainFooter()




Use to execute code after declarations




``` Hack
public function setPseudoMainFooter(
  string $code,
): this;
```




Useful for scripts; eg:
setPseudoMainFooter((new MyScript())->main($argv));




## Parameters




+ ` string $code `




## Returns




* ` this `