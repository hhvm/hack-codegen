---
layout: docs
title: Facebook\HackCodegen\IHackBuilderKeyRenderer
id: interface.Facebook.HackCodegen.IHackBuilderKeyRenderer
docid: interface.Facebook.HackCodegen.IHackBuilderKeyRenderer
permalink: /docs/reference/interface.Facebook.HackCodegen.IHackBuilderKeyRenderer/
---
# Facebook\\HackCodegen\\IHackBuilderKeyRenderer




Interface for converting a value into code, when the value is required
to be a valid ` arraykey `




This does not extend ` IHackBuilderValueRenderer ` so ensure that callsites
explictly specify which renderer is the key renderer, and which is the
value renderer.




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

interface IHackBuilderKeyRenderer {...}
```




### Public Methods




* [` ->render(IHackCodegenConfig $config, \T $input): string `](<interface.Facebook.HackCodegen.IHackBuilderKeyRenderer.render.md>)