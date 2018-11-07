---
layout: docs
title: addOriginalFile
id: class.Facebook.HackCodegen.CodegenFile.addOriginalFile
docid: class.Facebook.HackCodegen.CodegenFile.addOriginalFile
permalink: /docs/reference/class.Facebook.HackCodegen.CodegenFile.addOriginalFile/
---
# Facebook\\HackCodegen\\CodegenFile::addOriginalFile()




Use this when refactoring generated code




``` Hack
public function addOriginalFile(
  string $file_name,
): this;
```




Say you're renaming a class, but
want to pull the manual code sections from the old file.  Use this.




## Parameters




* ` string $file_name `




## Returns




- ` this `