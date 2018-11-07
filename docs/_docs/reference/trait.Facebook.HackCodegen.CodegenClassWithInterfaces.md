---
layout: docs
title: Facebook\HackCodegen\CodegenClassWithInterfaces
id: trait.Facebook.HackCodegen.CodegenClassWithInterfaces
docid: trait.Facebook.HackCodegen.CodegenClassWithInterfaces
permalink: /docs/reference/trait.Facebook.HackCodegen.CodegenClassWithInterfaces/
---
# Facebook\\HackCodegen\\CodegenClassWithInterfaces




Functionality shared by all class-like definitions that are able to
implement interfaces




For example, classes and traits can implement interfaces, but enums
can't.




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

trait CodegenClassWithInterfaces {...}
```




### Public Methods




+ [` ->addInterface(CodegenImplementsInterface $value): \this `](<trait.Facebook.HackCodegen.CodegenClassWithInterfaces.addInterface.md>)
+ [` ->addInterfaces(\ Traversable<CodegenImplementsInterface> $interfaces): \this `](<trait.Facebook.HackCodegen.CodegenClassWithInterfaces.addInterfaces.md>)
+ [` ->getImplements(): vec<string> `](<trait.Facebook.HackCodegen.CodegenClassWithInterfaces.getImplements.md>)\
  Return the list of interfaces implemented by the generated class
+ [` ->setInterfaces(\ Traversable<CodegenImplementsInterface> $value): \this `](<trait.Facebook.HackCodegen.CodegenClassWithInterfaces.setInterfaces.md>)







### Protected Methods




* [` ->renderInterfaceList(HackBuilder $builder, string $introducer): \void `](<trait.Facebook.HackCodegen.CodegenClassWithInterfaces.renderInterfaceList.md>)