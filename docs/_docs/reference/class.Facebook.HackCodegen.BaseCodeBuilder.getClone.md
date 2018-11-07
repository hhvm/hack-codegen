---
layout: docs
title: getClone
id: class.Facebook.HackCodegen.BaseCodeBuilder.getClone
docid: class.Facebook.HackCodegen.BaseCodeBuilder.getClone
permalink: /docs/reference/class.Facebook.HackCodegen.BaseCodeBuilder.getClone/
---
# Facebook\\HackCodegen\\BaseCodeBuilder::getClone()




Create a new builder for the same scope, but a new buffer




``` Hack
final protected function getClone(): this;
```




clone() doesn't work as they end up sharing the same String Buffer, sharing
all the history (code already added to it).
So, if code is detached from the clone, it gets detached from original
builder as well.
This doesn't bother about the history, just copies the settings.




## Returns




- ` this `