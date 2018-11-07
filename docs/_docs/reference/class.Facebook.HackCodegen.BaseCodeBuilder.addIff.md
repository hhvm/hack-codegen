
***

layout: docs
title: addIff
permalink: /docs/reference/class.Facebook.HackCodegen.BaseCodeBuilder.addIff.md
---







# Facebook\\HackCodegen\\BaseCodeBuilder::addIff()




If the condition is true, add code to the buffer using a %-placeholder
format string and arguments; otherwise, do nothing




``` Hack
final public function addIff(
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