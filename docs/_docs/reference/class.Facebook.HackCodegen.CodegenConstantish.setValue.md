---
layout: docs
title: setValue
id: class.Facebook.HackCodegen.CodegenConstantish.setValue
docid: class.Facebook.HackCodegen.CodegenConstantish.setValue
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenConstantish.setValue/
---
# Facebook\\HackCodegen\\CodegenConstantish::setValue()




Set the value of the constant using a renderer




``` Hack
public function setValue<T>(
  T $value,
      IHackBuilderValueRenderer<T> $renderer,
): this;
```




## Parameters




- ` T $value `
- ` IHackBuilderValueRenderer<T> $renderer ` a renderer for the value. In general, this should be
  created using `` HackBuilderValues ``




## Returns




+ ` this `