---
layout: docs
title: addGenerics
id: class.Facebook.HackCodegen.CodegenClassish.addGenerics
docid: class.Facebook.HackCodegen.CodegenClassish.addGenerics
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenClassish.addGenerics/
---
# Facebook\\HackCodegen\\CodegenClassish::addGenerics()




Add generic parameters




``` Hack
public function addGenerics(
      Traversable<string> $generics_decl,
): this;
```




For example:




```
$class->addGenerics(vec['TRead', 'TWrite as T']);
```




Will generate:




```
class MyClass<TRead, TWrite as T> {
```




## Parameters




- ` Traversable<string> $generics_decl `




## Returns




+ ` this `