---
layout: docs
title: keyValueArray
id: class.Facebook.HackCodegen.HackBuilderValues.keyValueArray
docid: class.Facebook.HackCodegen.HackBuilderValues.keyValueArray
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilderValues.keyValueArray/
---
# Facebook\\HackCodegen\\HackBuilderValues::keyValueArray()




Render a [` dict `](<class.Facebook.HackCodegen.HackBuilderValues.dict.md>)-like PHP array literal




``` Hack
public static function keyValueArray<Tk as arraykey, Tv>(
      IHackBuilderKeyRenderer<Tk> $kr,
      IHackBuilderValueRenderer<Tv> $vr,
): IHackBuilderValueRenderer<array<Tk, Tv>>;
```




## Parameters




* ` IHackBuilderKeyRenderer<Tk> $kr `
* ` IHackBuilderValueRenderer<Tv> $vr `




## Returns




- ` IHackBuilderValueRenderer<array<Tk, Tv>> `