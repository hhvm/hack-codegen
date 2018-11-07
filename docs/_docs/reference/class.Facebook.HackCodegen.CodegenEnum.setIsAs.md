---
layout: docs
title: setIsAs
id: class.Facebook.HackCodegen.CodegenEnum.setIsAs
docid: class.Facebook.HackCodegen.CodegenEnum.setIsAs
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenEnum.setIsAs/
---
# Facebook\\HackCodegen\\CodegenEnum::setIsAs()




Make the enum usable directly as the specified type




``` Hack
public function setIsAs(
  string $is_as,
): this;
```




For example, ` ->setIsAs('string') ` will declare the enum as `` as string ``,
allowing values to be directly passed into functions that take a ``` string ```.




## Parameters




* ` string $is_as `




## Returns




- ` this `