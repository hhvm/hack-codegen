---
layout: docs
title: immMap
id: class.Facebook.HackCodegen.HackBuilderValues.immMap
docid: class.Facebook.HackCodegen.HackBuilderValues.immMap
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilderValues.immMap/
---
# Facebook\\HackCodegen\\HackBuilderValues::immMap()




Render an ` ImmMap ` literal




``` Hack
public static function immMap<Tk as arraykey, Tv>(
      IHackBuilderKeyRenderer<Tk> $kr,
      IHackBuilderValueRenderer<Tv> $vr,
): IHackBuilderValueRenderer<ImmMap<Tk, Tv>>;
```




## Parameters




* ` IHackBuilderKeyRenderer<Tk> $kr `
* ` IHackBuilderValueRenderer<Tv> $vr `




## Returns




- ` IHackBuilderValueRenderer<ImmMap<Tk, Tv>> `