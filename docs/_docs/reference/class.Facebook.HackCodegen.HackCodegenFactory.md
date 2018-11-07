---
layout: docs
title: Facebook\HackCodegen\HackCodegenFactory
id: class.Facebook.HackCodegen.HackCodegenFactory
docid: class.Facebook.HackCodegen.HackCodegenFactory
permalink: /docs/reference/class.Facebook.HackCodegen.HackCodegenFactory/
---
# Facebook\\HackCodegen\\HackCodegenFactory




An ` IHackCodegenFactory ` that takes a configuration object




To avoid needing to specify the configuration at every call site, you
can create your own class using ` CodegenFactoryTrait `, or directly
implement `` ICodegenFactory ``.




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class HackCodegenFactory {...}
```




### Public Methods




* [` ->__construct(IHackCodegenConfig $config) `](<class.Facebook.HackCodegen.HackCodegenFactory.__construct.md>)
* [` ->getConfig(): IHackCodegenConfig `](<class.Facebook.HackCodegen.HackCodegenFactory.getConfig.md>)