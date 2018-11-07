---
layout: docs
title: Facebook\HackCodegen\HackCodegenConfig
id: class.Facebook.HackCodegen.HackCodegenConfig
docid: class.Facebook.HackCodegen.HackCodegenConfig
permalink: /docs/reference/class.Facebook.HackCodegen.HackCodegenConfig/
---
# Facebook\\HackCodegen\\HackCodegenConfig




This class contains the default configuration for Hack code generation




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class HackCodegenConfig implements IHackCodegenConfig {...}
```




### Public Methods




+ [` ->getFileHeader(): ?vec<string> `](<class.Facebook.HackCodegen.HackCodegenConfig.getFileHeader.md>)
+ [` ->getFormatter(): ?ICodegenFormatter `](<class.Facebook.HackCodegen.HackCodegenConfig.getFormatter.md>)
+ [` ->getMaxLineLength(): int `](<class.Facebook.HackCodegen.HackCodegenConfig.getMaxLineLength.md>)
+ [` ->getRootDir(): string `](<class.Facebook.HackCodegen.HackCodegenConfig.getRootDir.md>)
+ [` ->getSpacesPerIndentation(): int `](<class.Facebook.HackCodegen.HackCodegenConfig.getSpacesPerIndentation.md>)
+ [` ->shouldUseTabs(): bool `](<class.Facebook.HackCodegen.HackCodegenConfig.shouldUseTabs.md>)
+ [` ->withFormatter(ICodegenFormatter $formatter): \this `](<class.Facebook.HackCodegen.HackCodegenConfig.withFormatter.md>)
+ [` ->withRootDir(string $root): \this `](<class.Facebook.HackCodegen.HackCodegenConfig.withRootDir.md>)