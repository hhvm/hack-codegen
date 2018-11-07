---
layout: docs
title: startForeachLoop
id: class.Facebook.HackCodegen.HackBuilder.startForeachLoop
docid: class.Facebook.HackCodegen.HackBuilder.startForeachLoop
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilder.startForeachLoop/
---
# Facebook\\HackCodegen\\HackBuilder::startForeachLoop()




Start a foreach loop, generate the temporary variable assignement, then
it's equivalent to calling openBrace, which newline and indent




``` Hack
public function startForeachLoop(
  string $traversable,
  ?string $key,
  string $value,
): this;
```




## Parameters




* ` string $traversable `
* ` ?string $key `
* ` string $value `




## Returns




- ` this `