---
layout: docs
title: addDeclComment
id: class.Facebook.HackCodegen.CodegenClass.addDeclComment
docid: class.Facebook.HackCodegen.CodegenClass.addDeclComment
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenClass.addDeclComment/
---
# Facebook\\HackCodegen\\CodegenClass::addDeclComment()




Add a comment before the class




``` Hack
final public function addDeclComment(
  string $comment,
): this;
```




For example:




```
$class->addDeclComment('\/* HH_FIXME[4040] *\/');
```




## Parameters




+ ` string $comment ` the full comment, including delimiters




## Returns




* ` this `