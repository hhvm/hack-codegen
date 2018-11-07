---
layout: docs
title: addvf
id: class.Facebook.HackCodegen.BaseCodeBuilder.addvf
docid: class.Facebook.HackCodegen.BaseCodeBuilder.addvf
permalink: /docs/reference/class.Facebook.HackCodegen.BaseCodeBuilder.addvf/
---
# Facebook\\HackCodegen\\BaseCodeBuilder::addvf()




Add code to the buffer, using a % placeholder format string and
an array of arguments




``` Hack
final protected function addvf(
  string $code,
  array<mixed> $args,
): this;
```




This is unsafe. Use [` addf `](<class.Facebook.HackCodegen.BaseCodeBuilder.addf.md>) instead if you have a literal format string.




## Parameters




* ` string $code `
* ` array<mixed> $args `




## Returns




- ` this `