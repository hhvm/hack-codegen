/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use namespace HH\Lib\{C, Str, Vec};
use namespace Facebook\HackCodegen\_Private\Vec as VecP;

/** Class containing basic language-agnostic code generation functions.
 *
 * This should not be used directly; instantiable language-specific subclasses
 * should be used to generate code. For example, Hack code is generated using
 * the `HackBuilder` class.
 */
<<__ConsistentConstruct>>
abstract class BaseCodeBuilder {

  const string DELIMITER = "\0";

  private _Private\StrBuffer $code;
  private bool $isNewLine = true;
  private int $indentationLevel = 0;
  private bool $isInsideFunction = false;
  private bool $wasGetCodeCalled = false;

  final public function __construct(protected IHackCodegenConfig $config) {
    $this->code = new _Private\StrBuffer();
  }

  /** Append a new line.
   *
   * This will always append a new line, even if the previous character was
   * a new line.
   *
   * To add a new line character only if we are not at the start of a line, use
   * `ensureNewLine()`.
   */
  final public function newLine(): this {
    $this->code->append("\n");
    $this->isNewLine = true;
    return $this;
  }

  /**
   * If the cursor is not in a new line, it will insert a line break.
   */
  final public function ensureNewLine(): this {
    if (!$this->isNewLine) {
      $this->newLine();
    }
    return $this;
  }

  /**
   * Ensures that the cursor is in a new line right after an empty line.
   */
  final public function ensureEmptyLine(): this {
    return $this->ensureNewLine()->newLine();
  }

  /**
   * Add code to the buffer.
   *
   * It automatically deals with indentation, and the code may contain line
   * breaks.
   *
   * If code is `null`, nothing will be added.
   *
   * For format-string support, see `addf()`
   */
  final public function add(?string $code): this {
    if ($code === null) {
      return $this;
    }
    return $this->addf('%s', $code);
  }

  /**
   * Add the specified code with no additional processing.
   *
   * For example, if there is a newline, any following characters will not be
   * indented. This is useful for heredocs.
   *
   * @see addVerbatimf
   */
  final public function addVerbatim(string $code): this {
    $this->code->append($code);
    return $this;
  }

  /**
   * Add the specified code with a %-placeholder format string, but no further
   * processing.
   *
   * For example, if there is a newline, any following characters will not be
   * indented. This is useful for heredocs.
   *
   * @see addVerbatim
   */
  final public function addVerbatimf(
    Str\SprintfFormatString $code,
    mixed ...$args
  ): this {
    $this->code->append(\vsprintf($code, $args));
    return $this;
  }

  /** Add code to the buffer, using a % placeholder format string. */
  final public function addf(
    Str\SprintfFormatString $code,
    mixed ...$args
  ): this {
    return $this->addvf((string)$code, $args);
  }

  /** Add code to the buffer, using a % placeholder format string and
   * an array of arguments.
   *
   * This is unsafe. Use `addf` instead if you have a literal format string.
   */
  final protected function addvf(string $code, vec<mixed> $args): this {
    if ($code === null) {
      return $this;
    }
    $code = \vsprintf($code, $args);

    // break into lines and add one by one to handle indentation
    $lines = Str\split($code, "\n");
    $last_line = \array_pop(inout $lines);
    foreach ($lines as $line) {
      $this->addLine($line);
    }

    if (\trim($last_line) === '') {
      return $this;
    }

    // if we're in a new line, insert indentation
    if ($this->isNewLine) {
      if ($this->indentationLevel !== 0) {
        if ($this->config->shouldUseTabs()) {
          $this->code->append(Str\repeat("\t", $this->indentationLevel));
        } else {
          $n =
            $this->config->getSpacesPerIndentation() * $this->indentationLevel;
          $this->code->append(Str\repeat(' ', $n));
        }
      }
      $this->isNewLine = false;
    }

    $this->code->append($last_line);

    return $this;
  }

  /**
   * If the condition is true, add code to the buffer; otherwise, do nothing.
   */
  final public function addIf(bool $condition, string $code): this {
    if ($condition) {
      $this->add($code);
    }
    return $this;
  }

  /**
   * If the condition is true, add code to the buffer using a %-placeholder
   * format string and arguments; otherwise, do nothing.
   */
  final public function addIff(
    bool $condition,
    Str\SprintfFormatString $code,
    mixed ...$args
  ): this {
    if ($condition) {
      $this->addvf((string)$code, $args);
    }
    return $this;
  }

  /**
   * Add code using the %-placeholder format string and array of values, then
   * insert a newline. */
  final protected function addLineImplvf(
    ?string $code,
    vec<mixed> $args,
  ): this {
    return $this->addvf((string)$code, $args)->newLine();
  }

  /**
   * If the condition is true, append the code followed by a newline.
   *
   * @see addLineIff
   */
  final public function addLineIf(bool $condition, string $code): this {
    return $this->addLineIff($condition, '%s', $code);
  }

  /**
   * If the condition is true, append code to the buffer using a %-placeholder
   * format string and arguments, followed by a newline.
   */
  final public function addLineIff(
    bool $condition,
    Str\SprintfFormatString $code,
    mixed ...$args
  ): this {
    if ($condition) {
      $this->addLineImplvf((string)$code, $args);
    }
    return $this;
  }

  /**
   * Add the code to the buffer followed by a new line.
   *
   * If code is `null`, nothing will be added.
   * For %-placeholder format strings, use `addLinef()`.
   */
  final public function addLine(?string $code): this {
    if ($code === null) {
      return $this;
    }
    return $this->addLinef('%s', $code);
  }

  /** Add code specified using a %-placeholder format string and arguments,
   * followed by a newline */
  final public function addLinef(
    Str\SprintfFormatString $code,
    mixed ...$args
  ): this {
    return $this->addLineImplvf((string)$code, $args);
  }

  /**
   * Add each element of the `Traversable` as a new line
   */
  final public function addLines(Traversable<string> $lines): this {
    foreach ($lines as $line) {
      $this->addLine($line);
    }
    return $this;
  }

  /**
   * Indicate that the code that will follow will be inside a function, so that
   * the indentation is taken into account for the max line length.
   */
  final public function setIsInsideFunction(): this {
    $this->isInsideFunction = true;
    return $this;
  }

  /**
   * Insert code with %-placeholder format strings and suggested line breaks.
   *
   * @see `addWithSuggestedLineBreaks` for details on the behavior.
   */
  final public function addWithSuggestedLineBreaksf(
    Str\SprintfFormatString $code,
    mixed ...$args
  ): this {
    return $this->addWithSuggestedLineBreaks(\vsprintf($code, $args));
  }

  /**
   * Let's the user suggest linebreaks in the code string provided, marked by
   * the delimiter. The max length is calculated based on the current
   * indentation level.
   *
   * If the code string exceeds the max length
   *        - Preferentially uses the delimiter to break the line
   *        - If some part is too big to fit, it lets it be.
   * If the code string or a part doesn't exceed the max length
   *        - Replaces the delimiter with space.
   *
   * The delimiter is `BaseCodeBuilder::DELIMITER`.
   */
  final public function addWithSuggestedLineBreaks(?string $code): this {
    if ($code === null) {
      return $this;
    }

    // If there's more than 1 line, add them 1 by 1
    $lines = Str\split($code, "\n");
    if (C\count($lines) > 1) {
      // The last line shouldn't have a finishing end line,
      // so add it manually
      $last_line = VecP\pop_backx(inout $lines);
      $this->addLinesWithSuggestedLineBreaks($lines);
      return $this->addWithSuggestedLineBreaks($last_line);
    }

    // Subtracting two to allow space for indenting and/or opening braces.
    $max_length = $this->getMaxCodeLength() - 2;
    if ($this->isInsideFunction) {
      // Please note that this method is called both from inside a function or
      // stuff like class/func declaration.
      $max_length = $max_length - 2;
    }

    $lines_with_sugg_breaks = \explode(self::DELIMITER, $code);
    $final_lines = vec[];
    foreach ($lines_with_sugg_breaks as $line) {
      if (!$line) {
        // Continue if the line is empty
        continue;
      }
      $line = $line as string;
      if (C\is_empty($final_lines)) {
        $final_lines[] = $line;
      } else {
        $last_line = C\lastx($final_lines);
        $final_lines = Vec\take($final_lines, C\count($final_lines) - 1);
        $composite_line = $last_line.' '.$line;
        if (\strlen($composite_line) > $max_length) {
          $final_lines[] = $last_line;
          $final_lines[] = $line;
        } else {
          // Concatenate the line to the last line
          $final_lines[] = $composite_line;
        }
      }
    }
    return $this->add(Str\join($final_lines, "\n  "));
  }

  /**
   * Similar to addWithSuggestedLineBreaks but allows to add more than one
   * line at a time.  See that method for more information.
   */
  final public function addLinesWithSuggestedLineBreaks(
    Traversable<string> $lines,
  ): this {
    foreach ($lines as $line) {
      $this->addWithSuggestedLineBreaks($line)->newLine();
    }
    return $this;
  }

  /** The logical indentation level.
   *
   * How many levels the current code is nested. For the number of spaces
   * used for a single logical indentation, see
  * `IHackCodegenConfig::getSpacesPerIndentation()`.
  */
  final protected function setIndentationLevel(int $level): void {
    $this->indentationLevel = $level;
  }

  /** Returns true if any lines are longer than the maximum length */
  final protected static function checkIfLineIsTooLong(
    string $code,
    int $max_length,
  ): bool {
    foreach (Str\split($code, "\n") as $line) {
      if (Str\length($line) > $max_length) {
        return true;
      }
    }
    return false;
  }

  /**
   * Returns the maximum length of code based on the current identation level.
   */
  final protected function getMaxCodeLength(): int {
    return $this->config->getMaxLineLength() -
      $this->config->getSpacesPerIndentation() * $this->indentationLevel;
  }

  /**
   * Increase the logical indentation level.
   *
   * @see unindent()
   */
  final public function indent(): this {
    $this->indentationLevel++;
    return $this;
  }

  /**
   * Decrease the logical indentation level.
   *
   * @see indent()
   */
  final public function unindent(): this {
    invariant(
      $this->indentationLevel >= 1,
      'Indentation level cannot go below zero.',
    );
    $this->indentationLevel--;
    return $this;
  }

  /**
   * Get all the code that has been appended to the buffer.
   *
   * This may only be called once.
   */
  final public function getCode(): string {
    invariant(
      !$this->wasGetCodeCalled,
      'You may only call getCode() once on a given HackBuilder object.',
    );
    $this->wasGetCodeCalled = true;
    return $this->code->detach();
  }

  /**
   * Create a new builder for the same scope, but a new buffer.
   *
   * clone() doesn't work as they end up sharing the same String Buffer, sharing
   * all the history (code already added to it).
   * So, if code is detached from the clone, it gets detached from original
   * builder as well.
   * This doesn't bother about the history, just copies the settings.
   */
  final protected function getClone(): this {
    $builder = new static($this->config);
    if ($this->isInsideFunction) {
      $builder->setIsInsideFunction();
    }
    $builder->setIndentationLevel($this->indentationLevel);
    return $builder;
  }

  /**
   * Add to the buffer the begin of a manual section with the specified id.
   */
  public function startManualSection(string $id): this {
    return $this
      ->ensureNewLine()
      ->addLine(PartiallyGeneratedCode::getBeginManualSection($id));
  }

  /**
   * Add to the buffer the end of a manual section.
   */
  public function endManualSection(): this {
    return $this
      ->ensureNewLine()
      ->addLine(PartiallyGeneratedCode::getEndManualSection());
  }
}
