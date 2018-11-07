---
layout: docs
title: Facebook\HackCodegen\BaseCodeBuilder
id: class.Facebook.HackCodegen.BaseCodeBuilder
docid: class.Facebook.HackCodegen.BaseCodeBuilder
permalink: /docs/reference/class.Facebook.HackCodegen.BaseCodeBuilder/
---
# Facebook\\HackCodegen\\BaseCodeBuilder




Class containing basic language-agnostic code generation functions




This should not be used directly; instantiable language-specific subclasses
should be used to generate code. For example, Hack code is generated using
the ` HackBuilder ` class.




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

abstract class BaseCodeBuilder {...}
```




### Public Methods




+ [` ->__construct(IHackCodegenConfig $config) `](<class.Facebook.HackCodegen.BaseCodeBuilder.__construct.md>)
+ [` ->add(?string $code): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.add.md>)\
  Add code to the buffer
+ [` ->addIf(bool $condition, string $code): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.addIf.md>)\
  If the condition is true, add code to the buffer; otherwise, do nothing
+ [` ->addIff(bool $condition, \HH\Lib\Str\SprintfFormatString $code, \mixed ...$args): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.addIff.md>)\
  If the condition is true, add code to the buffer using a %-placeholder
  format string and arguments; otherwise, do nothing
+ [` ->addLine(?string $code): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.addLine.md>)\
  Add the code to the buffer followed by a new line
+ [` ->addLineIf(bool $condition, string $code): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.addLineIf.md>)\
  If the condition is true, append the code followed by a newline
+ [` ->addLineIff(bool $condition, \HH\Lib\Str\SprintfFormatString $code, \mixed ...$args): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.addLineIff.md>)\
  If the condition is true, append code to the buffer using a %-placeholder
  format string and arguments, followed by a newline
+ [` ->addLinef(\HH\Lib\Str\SprintfFormatString $code, \mixed ...$args): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.addLinef.md>)\
  Add code specified using a %-placeholder format string and arguments,
  followed by a newline
+ [` ->addLines(Traversable<string> $lines): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.addLines.md>)\
  Add each element of the `` Traversable `` as a new line
+ [` ->addLinesWithSuggestedLineBreaks(\ Traversable<string> $lines): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.addLinesWithSuggestedLineBreaks.md>)\
  Similar to addWithSuggestedLineBreaks but allows to add more than one
  line at a time
+ [` ->addVerbatim(string $code): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.addVerbatim.md>)\
  Add the specified code with no additional processing
+ [` ->addVerbatimf(\HH\Lib\Str\SprintfFormatString $code, \mixed ...$args): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.addVerbatimf.md>)\
  Add the specified code with a %-placeholder format string, but no further
  processing
+ [` ->addWithSuggestedLineBreaks(?string $code): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.addWithSuggestedLineBreaks.md>)\
  Let's the user suggest linebreaks in the code string provided, marked by
  the delimiter
+ [` ->addWithSuggestedLineBreaksf(\HH\Lib\Str\SprintfFormatString $code, \mixed ...$args): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.addWithSuggestedLineBreaksf.md>)\
  Insert code with %-placeholder format strings and suggested line breaks
+ [` ->addf(\HH\Lib\Str\SprintfFormatString $code, \mixed ...$args): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.addf.md>)\
  Add code to the buffer, using a % placeholder format string
+ [` ->endManualSection(): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.endManualSection.md>)\
  Add to the buffer the end of a manual section
+ [` ->ensureEmptyLine(): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.ensureEmptyLine.md>)\
  Ensures that the cursor is in a new line right after an empty line
+ [` ->ensureNewLine(): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.ensureNewLine.md>)\
  If the cursor is not in a new line, it will insert a line break
+ [` ->getCode(): string `](<class.Facebook.HackCodegen.BaseCodeBuilder.getCode.md>)\
  Get all the code that has been appended to the buffer
+ [` ->indent(): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.indent.md>)\
  Increase the logical indentation level
+ [` ->newLine(): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.newLine.md>)\
  Append a new line
+ [` ->setIsInsideFunction(): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.setIsInsideFunction.md>)\
  Indicate that the code that will follow will be inside a function, so that
  the indentation is taken into account for the max line length
+ [` ->startManualSection(string $id): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.startManualSection.md>)\
  Add to the buffer the begin of a manual section with the specified id
+ [` ->unindent(): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.unindent.md>)\
  Decrease the logical indentation level







### Protected Methods




* [` ::checkIfLineIsTooLong(string $code, int $max_length): bool `](<class.Facebook.HackCodegen.BaseCodeBuilder.checkIfLineIsTooLong.md>)\
  Returns true if any lines are longer than the maximum length
* [` ->addLineImplvf(?string $code, \array<\mixed> $args): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.addLineImplvf.md>)\
  Add code using the %-placeholder format string and array of values, then
  insert a newline
* [` ->addvf(string $code, \array<\mixed> $args): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.addvf.md>)\
  Add code to the buffer, using a % placeholder format string and
  an array of arguments
* [` ->getClone(): \this `](<class.Facebook.HackCodegen.BaseCodeBuilder.getClone.md>)\
  Create a new builder for the same scope, but a new buffer
* [` ->getMaxCodeLength(): int `](<class.Facebook.HackCodegen.BaseCodeBuilder.getMaxCodeLength.md>)\
  Returns the maximum length of code based on the current identation level
* [` ->setIndentationLevel(int $level): \void `](<class.Facebook.HackCodegen.BaseCodeBuilder.setIndentationLevel.md>)\
  The logical indentation level