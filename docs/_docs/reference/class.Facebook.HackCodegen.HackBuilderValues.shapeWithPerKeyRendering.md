---
layout: docs
title: shapeWithPerKeyRendering
id: class.Facebook.HackCodegen.HackBuilderValues.shapeWithPerKeyRendering
docid: class.Facebook.HackCodegen.HackBuilderValues.shapeWithPerKeyRendering
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilderValues.shapeWithPerKeyRendering/
---
# Facebook\\HackCodegen\\HackBuilderValues::shapeWithPerKeyRendering()




Render a ` shape() ` literal with a different renderer for each shape key




``` Hack
public static function shapeWithPerKeyRendering(
  shape(
) $value_renderers,
): IHackBuilderValueRenderer<shape(
)>;
```




## Parameters




* ` shape( ) $value_renderers ` a shape with the same keys as the literal, but
  with appropriate `` IHackBuilderValueRenderer ``s for the value.




## Returns




- ` IHackBuilderValueRenderer<shape( )> `