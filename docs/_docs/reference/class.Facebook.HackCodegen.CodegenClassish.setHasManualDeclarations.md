---
layout: docs
title: setHasManualDeclarations
id: class.Facebook.HackCodegen.CodegenClassish.setHasManualDeclarations
docid: class.Facebook.HackCodegen.CodegenClassish.setHasManualDeclarations
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenClassish.setHasManualDeclarations/
---
# Facebook\\HackCodegen\\CodegenClassish::setHasManualDeclarations()




If value is set to true, the class will have a section for manually adding
declarations, such as type constants




``` Hack
public function setHasManualDeclarations(
  bool $value = true,
  ?string $name = NULL,
  ?string $contents = NULL,
): this;
```




Manual declaration sections appear at the top of the class.




You may specify a name for the section, which will appear in
the comment and is used to merge the code when re-generating it.
You may also specify a default content for the manual section, e.g.
a comment indicating that additional declarations should be placed there.




## Parameters




+ ` bool $value = true `
+ ` ?string $name = NULL `
+ ` ?string $contents = NULL `




## Returns




* ` this `