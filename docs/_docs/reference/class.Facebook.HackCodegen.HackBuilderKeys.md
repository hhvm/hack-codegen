---
layout: docs
title: Facebook\HackCodegen\HackBuilderKeys
id: class.Facebook.HackCodegen.HackBuilderKeys
docid: class.Facebook.HackCodegen.HackBuilderKeys
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilderKeys/
---
# Facebook\\HackCodegen\\HackBuilderKeys




Factory class for creating ` IHackBuilderKeyRenderer ` instances




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

abstract final class HackBuilderKeys {...}
```




### Public Methods




+ [` ::classname(): \IHackBuilderKeyRenderer<string> `](<class.Facebook.HackCodegen.HackBuilderKeys.classname.md>)\
  Assume the key is a classname, and render a `` ::class `` constant
+ [` ::export(): \IHackBuilderKeyRenderer<\arraykey> `](<class.Facebook.HackCodegen.HackBuilderKeys.export.md>)\
  Render the key as Hack code that produces the same value
+ [` ::lambda<\T as arraykey>(\callable $render): \IHackBuilderKeyRenderer<\T> `](<class.Facebook.HackCodegen.HackBuilderKeys.lambda.md>)\
  The key will be rendered with the specified lambda
+ [` ::literal(): \IHackBuilderKeyRenderer<string> `](<class.Facebook.HackCodegen.HackBuilderKeys.literal.md>)\
  Render the key with no changes or escaping