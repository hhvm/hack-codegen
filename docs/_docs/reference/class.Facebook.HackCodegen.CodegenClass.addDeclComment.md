
***

layout: docs
title: addDeclComment
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenClass.addDeclComment.md
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