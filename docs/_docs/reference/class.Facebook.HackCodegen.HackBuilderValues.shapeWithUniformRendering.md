---
layout: docs
title: shapeWithUniformRendering
id: class.Facebook.HackCodegen.HackBuilderValues.shapeWithUniformRendering
docid: class.Facebook.HackCodegen.HackBuilderValues.shapeWithUniformRendering
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilderValues.shapeWithUniformRendering/
---
# Facebook\\HackCodegen\\HackBuilderValues::shapeWithUniformRendering()




Render a ` shape() ` literal where all values are rendered the same way




``` Hack
public static function shapeWithUniformRendering<Tv>(
      IHackBuilderValueRenderer<Tv> $vr,
): IHackBuilderValueRenderer<shape(
)>;
```




## Parameters




+ ` IHackBuilderValueRenderer<Tv> $vr `




## Returns




* ` IHackBuilderValueRenderer<shape( )> `