---
layout: docs
title: addInlineCommentWithStars
id: class.Facebook.HackCodegen.HackBuilder.addInlineCommentWithStars
docid: class.Facebook.HackCodegen.HackBuilder.addInlineCommentWithStars
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilder.addInlineCommentWithStars/
---
# Facebook\\HackCodegen\\HackBuilder::addInlineCommentWithStars()




Add a /*-style comment




``` Hack
public function addInlineCommentWithStars(
  ?string $comment,
): this;
```




You probably don't want to do this instead
of adding a docBlock or a //-style comment, but HH_FIXME requires
the star format soooooo here we are.




## Parameters




+ ` ?string $comment `




## Returns




* ` this `