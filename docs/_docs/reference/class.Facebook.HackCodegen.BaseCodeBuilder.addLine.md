---
layout: docs
title: addLine
id: class.Facebook.HackCodegen.BaseCodeBuilder.addLine
docid: class.Facebook.HackCodegen.BaseCodeBuilder.addLine
permalink: /docs/reference/class.Facebook.HackCodegen.BaseCodeBuilder.addLine/
---
# Facebook\\HackCodegen\\BaseCodeBuilder::addLine()




Add the code to the buffer followed by a new line




``` Hack
final public function addLine(
  ?string $code,
): this;
```




If code is ` null `, nothing will be added.
For %-placeholder format strings, use [` addLinef() `](<class.Facebook.HackCodegen.BaseCodeBuilder.addLinef.md>).




## Parameters




* ` ?string $code `




## Returns




- ` this `