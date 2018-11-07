---
layout: docs
title: merge
id: class.Facebook.HackCodegen.PartiallyGeneratedCode.merge
docid: class.Facebook.HackCodegen.PartiallyGeneratedCode.merge
permalink: /docs/reference/class.Facebook.HackCodegen.PartiallyGeneratedCode.merge/
---
# Facebook\\HackCodegen\\PartiallyGeneratedCode::merge()




Merge the code with the existing code




``` Hack
public function merge(
  string $existing_code,
  ?KeyedContainer<string, Traversable<string>> $rekeys = NULL,
): string;
```




The manual sections of
the existing code will be merged into the corresponding sections
of the new code.




If rekeys is specified, we will attempt to pull code from sections
with different names, as specified by the mapping.




## Parameters




+ ` string $existing_code `
+ ` ?KeyedContainer<string, Traversable<string>> $rekeys = NULL `




## Returns




* ` string `