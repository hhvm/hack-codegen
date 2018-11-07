---
layout: docs
title: setHasManualMethodSection
id: class.Facebook.HackCodegen.CodegenClassish.setHasManualMethodSection
docid: class.Facebook.HackCodegen.CodegenClassish.setHasManualMethodSection
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenClassish.setHasManualMethodSection/
---
# Facebook\\HackCodegen\\CodegenClassish::setHasManualMethodSection()




Set whether or not the class has a section to contain manually written
or modified methods




``` Hack
public function setHasManualMethodSection(
  bool $value = true,
  ?string $name = NULL,
  ?string $contents = NULL,
): this;
```




Manual method sections appear at the bottom of the class.




You may specify a name for the section, which will appear in
the comment and is used to merge the code when re-generating it.
You may also specify a default content for the manual section, e.g.
a comment indicating that additional methods should be placed there.




## Parameters




- ` bool $value = true `
- ` ?string $name = NULL `
- ` ?string $contents = NULL `




## Returns




+ ` this `