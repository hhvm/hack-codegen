---
layout: docs
title: newLine
id: class.Facebook.HackCodegen.BaseCodeBuilder.newLine
docid: class.Facebook.HackCodegen.BaseCodeBuilder.newLine
permalink: /docs/reference/class.Facebook.HackCodegen.BaseCodeBuilder.newLine/
---
# Facebook\\HackCodegen\\BaseCodeBuilder::newLine()




Append a new line




``` Hack
final public function newLine(): this;
```




This will always append a new line, even if the previous character was
a new line.




To add a new line character only if we are not at the start of a line, use
[` ensureNewLine() `](<class.Facebook.HackCodegen.BaseCodeBuilder.ensureNewLine.md>).




## Returns




+ ` this `