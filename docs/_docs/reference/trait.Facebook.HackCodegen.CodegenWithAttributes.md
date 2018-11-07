---
layout: docs
title: Facebook\HackCodegen\CodegenWithAttributes
id: trait.Facebook.HackCodegen.CodegenWithAttributes
docid: trait.Facebook.HackCodegen.CodegenWithAttributes
permalink: /docs/reference/trait.Facebook.HackCodegen.CodegenWithAttributes/
---
# Facebook\\HackCodegen\\CodegenWithAttributes




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

trait CodegenWithAttributes {...}
```




### Public Methods




* [` ->addEmptyUserAttribute(string $name): \this `](<trait.Facebook.HackCodegen.CodegenWithAttributes.addEmptyUserAttribute.md>)
* [` ->addUserAttribute<\T>(string $name, \ Traversable<\T> $values, \ IHackBuilderValueRenderer<\T> $renderer): \this `](<trait.Facebook.HackCodegen.CodegenWithAttributes.addUserAttribute.md>)
* [` ->getAttributes(): dict<string, vec<string>> `](<trait.Facebook.HackCodegen.CodegenWithAttributes.getAttributes.md>)
* [` ->getUserAttributes(): dict<string, vec<string>> `](<trait.Facebook.HackCodegen.CodegenWithAttributes.getUserAttributes.md>)
* [` ->hasAttributes(): bool `](<trait.Facebook.HackCodegen.CodegenWithAttributes.hasAttributes.md>)
* [` ->renderAttributes(): ?string `](<trait.Facebook.HackCodegen.CodegenWithAttributes.renderAttributes.md>)







### Protected Methods




- [` ->getExtraAttributes(): dict<string, vec<string>> `](<trait.Facebook.HackCodegen.CodegenWithAttributes.getExtraAttributes.md>)