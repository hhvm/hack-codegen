---
layout: docs
title: addGenericSubtypeConstraint
id: class.Facebook.HackCodegen.CodegenClassish.addGenericSubtypeConstraint
docid: class.Facebook.HackCodegen.CodegenClassish.addGenericSubtypeConstraint
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenClassish.addGenericSubtypeConstraint/
---
# Facebook\\HackCodegen\\CodegenClassish::addGenericSubtypeConstraint()




Add a generic parameter with subtype constraint




``` Hack
public function addGenericSubtypeConstraint(
  string $subtype,
  string $baseType,
): this;
```




Subtype constraint of two types ` T ` and `` U `` will be evaluated to
the following: ``` T as U ``` whereas this statement asserts
that ```` T ```` must be a subtype of ````` U `````.




## Parameters




- ` string $subtype `
- ` string $baseType `




## Returns




+ ` this `