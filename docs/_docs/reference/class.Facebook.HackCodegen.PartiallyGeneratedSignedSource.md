---
layout: docs
title: Facebook\HackCodegen\PartiallyGeneratedSignedSource
id: class.Facebook.HackCodegen.PartiallyGeneratedSignedSource
docid: class.Facebook.HackCodegen.PartiallyGeneratedSignedSource
permalink: /docs/reference/class.Facebook.HackCodegen.PartiallyGeneratedSignedSource/
---
# Facebook\\HackCodegen\\PartiallyGeneratedSignedSource




Similar to SignedSource, but it uses a different header to indicate that the
file is partially generated




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class PartiallyGeneratedSignedSource extends SignedSourceBase {...}
```




### Public Methods




+ [` ::getDocBlock(?string $comment = NULL): string `](<class.Facebook.HackCodegen.PartiallyGeneratedSignedSource.getDocBlock.md>)\
  Get the text for a doc block that can be used for a partially
  generated file







### Protected Methods




* [` ::getTokenName(): string `](<class.Facebook.HackCodegen.PartiallyGeneratedSignedSource.getTokenName.md>)
* [` ::preprocess(string $file_data): string `](<class.Facebook.HackCodegen.PartiallyGeneratedSignedSource.preprocess.md>)