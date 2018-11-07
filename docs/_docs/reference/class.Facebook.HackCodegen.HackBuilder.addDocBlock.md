---
layout: docs
title: addDocBlock
id: class.Facebook.HackCodegen.HackBuilder.addDocBlock
docid: class.Facebook.HackCodegen.HackBuilder.addDocBlock
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilder.addDocBlock/
---
# Facebook\\HackCodegen\\HackBuilder::addDocBlock()




Add a Doc Block in the buffer




``` Hack
public function addDocBlock(
  ?string $comment,
  ?int $max_length = NULL,
): this;
```




You just need to pass the text of the
comment inside.  It will take care of the indentation and splitting long
lines.  You can use line breaks in the comment.




## Parameters




- ` ?string $comment `
- ` ?int $max_length = NULL `




## Returns




+ ` this `