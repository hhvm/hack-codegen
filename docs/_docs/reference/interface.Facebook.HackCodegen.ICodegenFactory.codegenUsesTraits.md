---
layout: docs
title: codegenUsesTraits
id: interface.Facebook.HackCodegen.ICodegenFactory.codegenUsesTraits
docid: interface.Facebook.HackCodegen.ICodegenFactory.codegenUsesTraits
permalink: /docs/reference/interface.Facebook.HackCodegen.ICodegenFactory.codegenUsesTraits/
---
# Facebook\\HackCodegen\\ICodegenFactory::codegenUsesTraits()




Generate a 'use' statements, for adding traits into a class or another
trait




``` Hack
public function codegenUsesTraits(
      Traversable<string> $traits,
): Traversable<Facebook\HackCodegen\CodegenUsesTrait>;
```




## Parameters




- ` Traversable<string> $traits `




## Returns




+ ` Traversable<Facebook\HackCodegen\CodegenUsesTrait> `