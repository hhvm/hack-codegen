---
layout: docs
title: rekeyManualSection
id: class.Facebook.HackCodegen.CodegenFile.rekeyManualSection
docid: class.Facebook.HackCodegen.CodegenFile.rekeyManualSection
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenFile.rekeyManualSection/
---
# Facebook\\HackCodegen\\CodegenFile::rekeyManualSection()




Use this to pull manual code from a section keyed by $old_key and
place it in a section keyed by $new_key




``` Hack
public function rekeyManualSection(
  string $old_key,
  string $new_key,
): this;
```




Note that $old_key could even be in a separate file, if you use
addOriginalFile.




## Parameters




- ` string $old_key `
- ` string $new_key `




## Returns




+ ` this `