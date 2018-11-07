---
layout: docs
title: Facebook\HackCodegen\_Private\StrBuffer
id: class.Facebook.HackCodegen._Private.StrBuffer
docid: class.Facebook.HackCodegen._Private.StrBuffer
permalink: /docs/reference/class.Facebook.HackCodegen._Private.StrBuffer/
---
# Facebook\\HackCodegen\\_Private\\StrBuffer




Class for building a string, only permitting append operations




The string can be retrived once via the [` detach() `](<class.Facebook.HackCodegen._Private.StrBuffer.detach.md>) method.




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen\_Private;

class StrBuffer {...}
```




### Public Methods




* [` ->append(\mixed $value): \void `](<class.Facebook.HackCodegen._Private.StrBuffer.append.md>)
* [` ->detach(): string `](<class.Facebook.HackCodegen._Private.StrBuffer.detach.md>)\
  Return the build string