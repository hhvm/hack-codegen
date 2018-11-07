---
layout: docs
title: Facebook\HackCodegen\PartiallyGeneratedCode
id: class.Facebook.HackCodegen.PartiallyGeneratedCode
docid: class.Facebook.HackCodegen.PartiallyGeneratedCode
permalink: /docs/reference/class.Facebook.HackCodegen.PartiallyGeneratedCode/
---
# Facebook\\HackCodegen\\PartiallyGeneratedCode




Manage partially generated code




The main operation is to merge existing
code (that probably has some handwritten code) with generated code.




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class PartiallyGeneratedCode {...}
```




### Public Methods




* [` ::containsManualSection(string $code): bool `](<class.Facebook.HackCodegen.PartiallyGeneratedCode.containsManualSection.md>)
* [` ::getBeginManualSection(string $id): string `](<class.Facebook.HackCodegen.PartiallyGeneratedCode.getBeginManualSection.md>)
* [` ::getEndManualSection(): string `](<class.Facebook.HackCodegen.PartiallyGeneratedCode.getEndManualSection.md>)
* [` ->__construct(string $code) `](<class.Facebook.HackCodegen.PartiallyGeneratedCode.__construct.md>)
* [` ->assertValidManualSections(): \void `](<class.Facebook.HackCodegen.PartiallyGeneratedCode.assertValidManualSections.md>)\
  Validate the manual sections and throws PartiallyGeneratedCodeException
  if there are any errors (e
* [` ->extractGeneratedCode(): string `](<class.Facebook.HackCodegen.PartiallyGeneratedCode.extractGeneratedCode.md>)\
  Extract the generated code and returns it as a string
* [` ->merge(string $existing_code, ?KeyedContainer<string, Traversable<string>> $rekeys = NULL): string `](<class.Facebook.HackCodegen.PartiallyGeneratedCode.merge.md>)\
  Merge the code with the existing code







### Private Methods




- [` ::getBeginManualSectionRegex(string $regex): string `](<class.Facebook.HackCodegen.PartiallyGeneratedCode.getBeginManualSectionRegex.md>)
- [` ->extractManualCode(string $code): dict<string, string> `](<class.Facebook.HackCodegen.PartiallyGeneratedCode.extractManualCode.md>)\
  Extract manually generated code and returns a map of ids to chunks of code
- [` ->iterateCodeSections(string $code): \\Generator<int, \tuple<?string, string>, \void> `](<class.Facebook.HackCodegen.PartiallyGeneratedCode.iterateCodeSections.md>)\
  Iterates through the code yielding tuples of ($id, $chunk), where
  $id is the id of the manual section or null if it's an auto-generated
  section, and chunk is the code belonging to that section