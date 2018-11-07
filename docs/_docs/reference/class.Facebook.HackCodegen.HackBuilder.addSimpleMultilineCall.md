
***

layout: docs
title: addSimpleMultilineCall
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilder.addSimpleMultilineCall.md
---







# Facebook\\HackCodegen\\HackBuilder::addSimpleMultilineCall()




Used in static function multilineCall where we don't know the current
indentation to wrap code correctly




``` Hack
private function addSimpleMultilineCall(
  string $name,
      Traversable<string> $params,
): this;
```




Adds a call (method, function, array construction, etc) where each param
is in one separate line.  It's enclosed in parens.  Trailing commas
are added to the params lines.




## Parameters




* ` string $name `
* ` Traversable<string> $params `




## Returns




- ` this `