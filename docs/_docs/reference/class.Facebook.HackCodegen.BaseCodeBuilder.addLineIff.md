---
layout: docs
title: addLineIff
id: class.Facebook.HackCodegen.BaseCodeBuilder.addLineIff
docid: class.Facebook.HackCodegen.BaseCodeBuilder.addLineIff
permalink: /docs/reference/class.Facebook.HackCodegen.BaseCodeBuilder.addLineIff/
---
# Facebook\\HackCodegen\\BaseCodeBuilder::addLineIff()




If the condition is true, append code to the buffer using a %-placeholder
format string and arguments, followed by a newline




``` Hack
final public function addLineIff(
  bool $condition,
  HH\Lib\Str\SprintfFormatString $code,
  mixed ...$args,
): this;
```




## Parameters




- ` bool $condition `
- ` HH\Lib\Str\SprintfFormatString $code `
- ` mixed ...$args `




## Returns




+ ` this `