---
layout: docs
title: addVerbatim
id: class.Facebook.HackCodegen.BaseCodeBuilder.addVerbatim
docid: class.Facebook.HackCodegen.BaseCodeBuilder.addVerbatim
permalink: /docs/reference/class.Facebook.HackCodegen.BaseCodeBuilder.addVerbatim/
---
# Facebook\\HackCodegen\\BaseCodeBuilder::addVerbatim()




Add the specified code with no additional processing




``` Hack
final public function addVerbatim(
  string $code,
): this;
```




For example, if there is a newline, any following characters will not be
indented. This is useful for heredocs.




## Parameters




+ ` string $code `




## Returns




* ` this `