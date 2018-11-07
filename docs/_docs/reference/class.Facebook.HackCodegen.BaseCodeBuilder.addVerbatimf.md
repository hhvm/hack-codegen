---
layout: docs
title: addVerbatimf
id: class.Facebook.HackCodegen.BaseCodeBuilder.addVerbatimf
docid: class.Facebook.HackCodegen.BaseCodeBuilder.addVerbatimf
permalink: /docs/reference/class.Facebook.HackCodegen.BaseCodeBuilder.addVerbatimf/
---
# Facebook\\HackCodegen\\BaseCodeBuilder::addVerbatimf()




Add the specified code with a %-placeholder format string, but no further
processing




``` Hack
final public function addVerbatimf(
  HH\Lib\Str\SprintfFormatString $code,
  mixed ...$args,
): this;
```




For example, if there is a newline, any following characters will not be
indented. This is useful for heredocs.




## Parameters




- ` HH\Lib\Str\SprintfFormatString $code `
- ` mixed ...$args `




## Returns




+ ` this `