---
layout: docs
title: addWrappedStringNoIndent
id: class.Facebook.HackCodegen.HackBuilder.addWrappedStringNoIndent
docid: class.Facebook.HackCodegen.HackBuilder.addWrappedStringNoIndent
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilder.addWrappedStringNoIndent/
---
# Facebook\\HackCodegen\\HackBuilder::addWrappedStringNoIndent()




Add a string that is auto-wrapped to not exceed the maximum length




``` Hack
public function addWrappedStringNoIndent(
  string $line,
  ?int $max_length = NULL,
): this;
```




The following lines will have the same level of indentation as the first
one. Example:




$this->callMethod(
'First line of the long code'.
'Second line of the long code'
);




## Parameters




+ ` string $line `
+ ` ?int $max_length = NULL `




## Returns




* ` this `