---
layout: docs
title: addConstructorWrapperFunc
id: class.Facebook.HackCodegen.CodegenClass.addConstructorWrapperFunc
docid: class.Facebook.HackCodegen.CodegenClass.addConstructorWrapperFunc
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenClass.addConstructorWrapperFunc/
---
# Facebook\\HackCodegen\\CodegenClass::addConstructorWrapperFunc()




Add a factory function to wrap instantiations of to the class




``` Hack
public function addConstructorWrapperFunc(
  ?Traversable<string> $params = NULL,
): this;
```




For example, if ` MyClass ` accepts a single `` string `` parameter, it would
generate:




```
function MyClass(string $s): MyClass {
  return new MyClass($s);
}
```




## Parameters




* ` ?Traversable<string> $params = NULL ` the parameters to generate, including types. If `` null ``,
  it will be inferred from the constructor if set.




## Returns




- ` this `