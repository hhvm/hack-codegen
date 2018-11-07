---
layout: docs
title: addWrappedString
id: class.Facebook.HackCodegen.HackBuilder.addWrappedString
docid: class.Facebook.HackCodegen.HackBuilder.addWrappedString
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilder.addWrappedString/
---
# Facebook\\HackCodegen\\HackBuilder::addWrappedString()




Add a string that is auto-wrapped to not exceed the maximum length




``` Hack
public function addWrappedString(
  string $line,
  ?int $max_length = NULL,
): this;
```




The following lines will have a level of indentation added. Example:




return 'First line of the long code'.
'Second line of the long code';




## Parameters




- ` string $line `
- ` ?int $max_length = NULL `




## Returns




+ ` this `