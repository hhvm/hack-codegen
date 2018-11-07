---
layout: docs
title: setValue
id: class.Facebook.HackCodegen.CodegenProperty.setValue
docid: class.Facebook.HackCodegen.CodegenProperty.setValue
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenProperty.setValue/
---
# Facebook\\HackCodegen\\CodegenProperty::setValue()




Set the initial value for the variable




``` Hack
public function setValue<T>(
  T $value,
      IHackBuilderValueRenderer<T> $renderer,
): this;
```




You can pass numbers, strings,
arrays, etc, and it will generate the code to render those values.




## Parameters




* ` T $value `
* ` IHackBuilderValueRenderer<T> $renderer `




## Returns




- ` this `