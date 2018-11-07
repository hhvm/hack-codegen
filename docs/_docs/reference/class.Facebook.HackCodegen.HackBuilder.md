---
layout: docs
title: Facebook\HackCodegen\HackBuilder
id: class.Facebook.HackCodegen.HackBuilder
docid: class.Facebook.HackCodegen.HackBuilder
permalink: /docs/reference/class.Facebook.HackCodegen.HackBuilder/
---
# Facebook\\HackCodegen\\HackBuilder




Class to facilitate building code




It has methods for some common patterns
used to generate code. It also deals with indentation and new lines.




## Interface Synopsis




``` Hack
namespace Facebook\HackCodegen;

final class HackBuilder extends BaseCodeBuilder {...}
```




### Public Methods




* [` ::multilineCall(IHackCodegenConfig $config, string $name, \ Traversable<string> $params, bool $close_statement = false): string `](<class.Facebook.HackCodegen.HackBuilder.multilineCall.md>)
* [` ->addAssignment<\T>(string $var_name, \T $value, \ IHackBuilderValueRenderer<\T> $renderer): \this `](<class.Facebook.HackCodegen.HackBuilder.addAssignment.md>)
* [` ->addCase<\T>(\T $case, \ IHackBuilderValueRenderer<\T> $formatter): \this `](<class.Facebook.HackCodegen.HackBuilder.addCase.md>)
* [` ->addCaseBlocks<\T>(\ Traversable<\T> $switch_values, \callable $func): \this `](<class.Facebook.HackCodegen.HackBuilder.addCaseBlocks.md>)
* [` ->addCatchBlock(string $class, string $variable): \this `](<class.Facebook.HackCodegen.HackBuilder.addCatchBlock.md>)\
  Start a catch block
* [` ->addDefault(): \this `](<class.Facebook.HackCodegen.HackBuilder.addDefault.md>)
* [` ->addDocBlock(?string $comment, ?int $max_length = NULL): \this `](<class.Facebook.HackCodegen.HackBuilder.addDocBlock.md>)\
  Add a Doc Block in the buffer
* [` ->addElseBlock(): \this `](<class.Facebook.HackCodegen.HackBuilder.addElseBlock.md>)\
  End current if/else block, and start a else block
* [` ->addElseIfBlock(string $condition): \this `](<class.Facebook.HackCodegen.HackBuilder.addElseIfBlock.md>)\
  End current if/else block, and start a 'else if (condition)' block
* [` ->addElseIfBlockf(\HH\Lib\Str\SprintfFormatString $condition, \mixed ...$args): \this `](<class.Facebook.HackCodegen.HackBuilder.addElseIfBlockf.md>)
* [` ->addFinallyBlock(): \this `](<class.Facebook.HackCodegen.HackBuilder.addFinallyBlock.md>)\
  Start a finally block
* [` ->addInlineComment(?string $comment): \this `](<class.Facebook.HackCodegen.HackBuilder.addInlineComment.md>)\
  Add a //-style comment
* [` ->addInlineCommentWithStars(?string $comment): \this `](<class.Facebook.HackCodegen.HackBuilder.addInlineCommentWithStars.md>)\
  Add a /*-style comment
* [` ->addMultilineCall(string $func_call_line, \ Traversable<string> $params, bool $include_close_statement = true): \this `](<class.Facebook.HackCodegen.HackBuilder.addMultilineCall.md>)\
  This method lets you call Multiline methods and also allows you to
  suggest line breaks
* [` ->addRenderer(ICodeBuilderRenderer $renderer): \this `](<class.Facebook.HackCodegen.HackBuilder.addRenderer.md>)
* [` ->addReturn<\T>(\T $value, \ IHackBuilderValueRenderer<\T> $renderer): \this `](<class.Facebook.HackCodegen.HackBuilder.addReturn.md>)
* [` ->addReturnf(\HH\Lib\Str\SprintfFormatString $value, \mixed ...$args): \this `](<class.Facebook.HackCodegen.HackBuilder.addReturnf.md>)
* [` ->addValue<\T>(\T $value, \IHackBuilderValueRenderer<\T> $r): \this `](<class.Facebook.HackCodegen.HackBuilder.addValue.md>)
* [` ->addWrappedString(string $line, ?int $max_length = NULL): \this `](<class.Facebook.HackCodegen.HackBuilder.addWrappedString.md>)\
  Add a string that is auto-wrapped to not exceed the maximum length
* [` ->addWrappedStringNoIndent(string $line, ?int $max_length = NULL): \this `](<class.Facebook.HackCodegen.HackBuilder.addWrappedStringNoIndent.md>)\
  Add a string that is auto-wrapped to not exceed the maximum length
* [` ->breakCase(): \this `](<class.Facebook.HackCodegen.HackBuilder.breakCase.md>)
* [` ->closeBrace(): \this `](<class.Facebook.HackCodegen.HackBuilder.closeBrace.md>)\
  Close a brace in a new line and sets one less level of indentation
* [` ->closeContainer(ContainerType $type): \this `](<class.Facebook.HackCodegen.HackBuilder.closeContainer.md>)
* [` ->closeStatement(): \this `](<class.Facebook.HackCodegen.HackBuilder.closeStatement.md>)
* [` ->endDefault(): \this `](<class.Facebook.HackCodegen.HackBuilder.endDefault.md>)
* [` ->endForeachLoop(): \this `](<class.Facebook.HackCodegen.HackBuilder.endForeachLoop.md>)\
  Strictly equivalent to calling closeBrace, which unindent and newline,
  but for readability, you should use this with startForeach
* [` ->endIfBlock(): \this `](<class.Facebook.HackCodegen.HackBuilder.endIfBlock.md>)\
  Strictly equivalent to calling closeBrace, which unindent and newline,
  but for readability, you should use this with startIfBlock
* [` ->endSwitch(): \this `](<class.Facebook.HackCodegen.HackBuilder.endSwitch.md>)
* [` ->endTryBlock(): \this `](<class.Facebook.HackCodegen.HackBuilder.endTryBlock.md>)\
  Strictly equivalent to calling closeBrace, which unindent and newline,
  but for readability, you should use this with startTryBlock
* [` ->openBrace(): \this `](<class.Facebook.HackCodegen.HackBuilder.openBrace.md>)\
  Open a brace in the current line and start a new line
  with one more level of indentation
* [` ->openContainer(ContainerType $type): \this `](<class.Facebook.HackCodegen.HackBuilder.openContainer.md>)
* [` ->returnCase<\T>(\T $value, \ IHackBuilderValueRenderer<\T> $r): \this `](<class.Facebook.HackCodegen.HackBuilder.returnCase.md>)
* [` ->returnCasef(\HH\Lib\Str\SprintfFormatString $value, \mixed ...$args): \this `](<class.Facebook.HackCodegen.HackBuilder.returnCasef.md>)
* [` ->startForeachLoop(string $traversable, ?string $key, string $value): \this `](<class.Facebook.HackCodegen.HackBuilder.startForeachLoop.md>)\
  Start a foreach loop, generate the temporary variable assignement, then
  it's equivalent to calling openBrace, which newline and indent
* [` ->startIfBlock(string $condition): \this `](<class.Facebook.HackCodegen.HackBuilder.startIfBlock.md>)\
  Start a if block, put the condition between the parenthesis, then
  it's equivalent to calling openBrace, which newline and indent
* [` ->startIfBlockf(\HH\Lib\Str\SprintfFormatString $condition, \mixed ...$args): \this `](<class.Facebook.HackCodegen.HackBuilder.startIfBlockf.md>)
* [` ->startSwitch(string $condition): \this `](<class.Facebook.HackCodegen.HackBuilder.startSwitch.md>)\
  Starts building a switch-statement that can loop over an Iterable
  to build each case-statement
* [` ->startTryBlock(): \this `](<class.Facebook.HackCodegen.HackBuilder.startTryBlock.md>)\
  Start try-catch-finally blocks in the code







### Private Methods




- [` ->addSimpleMultilineCall(string $name, \ Traversable<string> $params): \this `](<class.Facebook.HackCodegen.HackBuilder.addSimpleMultilineCall.md>)\
  Used in static function multilineCall where we don't know the current
  indentation to wrap code correctly
- [` ->addWrappedStringImpl(string $line, ?int $max_length = NULL, bool $indent_non_first_lines = true): \this `](<class.Facebook.HackCodegen.HackBuilder.addWrappedStringImpl.md>)
- [` ->assertIsVariable(string $name): \void `](<class.Facebook.HackCodegen.HackBuilder.assertIsVariable.md>)
- [` ->splitString(string $str, int $maxlen, bool $preserve_space = false): vec<string> `](<class.Facebook.HackCodegen.HackBuilder.splitString.md>)\
  Split a string on lines of at most $maxlen length