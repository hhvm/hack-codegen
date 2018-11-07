
***

layout: docs
title: startIfBlock
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilder.startIfBlock.md
---







# Facebook\\HackCodegen\\HackBuilder::startIfBlock()




Start a if block, put the condition between the parenthesis, then
it's equivalent to calling openBrace, which newline and indent




``` Hack
public function startIfBlock(
  string $condition,
): this;
```




startIfBlock('$a === 0') generates if ($a === 0) {\\n




## Parameters




+ ` string $condition `




## Returns




* ` this `