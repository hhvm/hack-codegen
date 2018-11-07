---
layout: docs
title: addMultilineCall
id: class.Facebook.HackCodegen.HackBuilder.addMultilineCall
docid: class.Facebook.HackCodegen.HackBuilder.addMultilineCall
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilder.addMultilineCall/
---
# Facebook\\HackCodegen\\HackBuilder::addMultilineCall()




This method lets you call Multiline methods and also allows you to
suggest line breaks




``` Hack
public function addMultilineCall(
  string $func_call_line,
      Traversable<string> $params,
  bool $include_close_statement = true,
): this;
```




It first tries to fit the call in a single line, then
by breaking at suggested line breaks.
If these don't materialize, it falls back to multi line calling and
uses suggested line breaks for each line individually.




One more thing that is different from vanilla Multilinecall is the
parameter which allows you to tell the function if closeStatement has
to be included in the call. This is important as it changes the way
we return code.




## Parameters




- ` string $func_call_line `
- ` Traversable<string> $params `
- ` bool $include_close_statement = true `




## Returns




+ ` this `