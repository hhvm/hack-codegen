---
layout: docs
title: dict
id: class.Facebook.HackCodegen.HackBuilderValues.dict
docid: class.Facebook.HackCodegen.HackBuilderValues.dict
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilderValues.dict/
---
# Facebook\\HackCodegen\\HackBuilderValues::dict()




Render a ` dict ` literal




``` Hack
public static function dict<Tk as arraykey, Tv>(
      IHackBuilderKeyRenderer<Tk> $kr,
      IHackBuilderValueRenderer<Tv> $vr,
): IHackBuilderValueRenderer<dict<Tk, Tv>>;
```




## Parameters




* ` IHackBuilderKeyRenderer<Tk> $kr `
* ` IHackBuilderValueRenderer<Tv> $vr `




## Returns




- ` IHackBuilderValueRenderer<dict<Tk, Tv>> `