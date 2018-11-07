---
layout: docs
title: splitString
id: class.Facebook.HackCodegen.HackBuilder.splitString
docid: class.Facebook.HackCodegen.HackBuilder.splitString
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilder.splitString/
---
# Facebook\\HackCodegen\\HackBuilder::splitString()




Split a string on lines of at most $maxlen length




``` Hack
private function splitString(
  string $str,
  int $maxlen,
  bool $preserve_space = false,
): vec<string>;
```




Line breaks in
the string will be respected.




## Parameters




- ` string $str `
- ` int $maxlen `
- ` bool $preserve_space = false `




## Returns




+ ` vec<string> `