
***

layout: docs
title: addGenericSupertypeConstraint
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenClassish.addGenericSupertypeConstraint.md
---







# Facebook\\HackCodegen\\CodegenClassish::addGenericSupertypeConstraint()




Add a generic parameter with supertype constraint




``` Hack
public function addGenericSupertypeConstraint(
  string $superType,
  string $subtype,
): this;
```




Supertype constraint of two types ` T ` and `` U `` will be evaluated to
the following: ``` T super U ``` whereas this statement asserts
that ```` T ```` must be a supertype of ````` U `````.




## Parameters




* ` string $superType `
* ` string $subtype `




## Returns




- ` this `