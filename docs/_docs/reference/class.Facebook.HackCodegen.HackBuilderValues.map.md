---
layout: docs
title: map
id: class.Facebook.HackCodegen.HackBuilderValues.map
docid: class.Facebook.HackCodegen.HackBuilderValues.map
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilderValues.map/
---
# Facebook\\HackCodegen\\HackBuilderValues::map()




Render a ` Map ` literal




``` Hack
public static function map<Tk as arraykey, Tv>(
      IHackBuilderKeyRenderer<Tk> $kr,
      IHackBuilderValueRenderer<Tv> $vr,
): IHackBuilderValueRenderer<Map<Tk, Tv>>;
```




## Parameters




- ` IHackBuilderKeyRenderer<Tk> $kr `
- ` IHackBuilderValueRenderer<Tv> $vr `




## Returns




+ ` IHackBuilderValueRenderer<Map<Tk, Tv>> `