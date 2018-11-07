---
layout: docs
title: add
id: class.Facebook.HackCodegen.BaseCodeBuilder.add
docid: class.Facebook.HackCodegen.BaseCodeBuilder.add
permalink: /docs/reference/class.Facebook.HackCodegen.BaseCodeBuilder.add/
---
# Facebook\\HackCodegen\\BaseCodeBuilder::add()




Add code to the buffer




``` Hack
final public function add(
  ?string $code,
): this;
```




It automatically deals with indentation, and the code may contain line
breaks.




If code is ` null `, nothing will be added.




For format-string support, see [` addf() `](<class.Facebook.HackCodegen.BaseCodeBuilder.addf.md>)




## Parameters




* ` ?string $code `




## Returns




- ` this `