<?hh // strict
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

namespace Facebook\HackCodegen;

abstract class BaseCodeBuilder implements ICodeBuilder {

  const string DELIMITER = "\t";

  private StrBuffer $code;
  private bool $isNewLine = true;
  private int $indentationLevel = 0;
  private bool $isInsideFunction = false;
  private bool $wasGetCodeCalled = false;

  final public function __construct(private IHackCodegenConfig $config) {
    $this->code = new StrBuffer();
  }

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
   * Add the code to the buffer (sprintf style may be used).
   * It automatically deals with indentation.  The code may contain line breaks.
   * If code is null, nothing will be added
   */
  /* HH_FIXME[4033] variadic params with type constraints are not supported */
  final public function add(?string $code, ...$args): this {
    return $this->addv($code, $args);
  }

  final protected function addv(?string $code, array<mixed> $args): this {
    if ($code === null) {
      return $this;
    }

    if (count($args)) {
      $code = vsprintf($code, $args);
    }

    // break into lines and add one by one to handle indentation
    $lines = explode("\n", $code);
    $last_line = array_pop($lines);
    foreach ($lines as $line) {
      $this->addLine($line);
    }

    if (trim($last_line) === '') {
      return $this;
    }

    // if we're in a new line, insert indentation
    if ($this->isNewLine) {
      if ($this->indentationLevel !== 0) {
        $n = $this->config->getSpacesPerIndentation() * $this->indentationLevel;
        $this->code->append(str_repeat(' ', $n));
      }
      $this->isNewLine = false;
    }

    $this->code->append($last_line);

    return $this;
  }

  /**
   * If the condition evaluates to true, the code will be added to the buffer.
   */
  final public function addIf(bool $condition, string $code, ...): this {
    if ($condition) {
      $this->addv($code, array_slice(func_get_args(), 2));
    }
    return $this;
  }

  final protected function addLineImpl(
    ?string $code,
    array<mixed> $args,
  ): this {
    return $this->addv($code, $args)->newLine();
  }

  /**
   * If the condition evaluates to true, the code will be added to the buffer
   * with a new line.
   */
  final public function addLineIf(bool $condition, string $code, ...): this {
    if ($condition) {
      $this->addLineImpl($code, array_slice(func_get_args(), 2));
    }
    return $this;
  }

  /**
   * Add the code to the buffer followed by a new line.
   * If code is null, nothing will be added
   */
  /* HH_FIXME[4033] variadic params with type constraints are not supported */
  final public function addLine(?string $code, ...$args): this {
    return $this->addLineImpl($code, $args);
  }

  /**
   * Add each element of the vector as a new line
   */
  final public function addLines(\ConstVector<string> $lines): this {
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
   * Let's the user suggest linebreaks in the code string provided, marked by
   * the delimiter. The max length is calculated based on the current
   * indentation level.
   * If the code string exceeds the max length
   *        - Preferentially uses the delimiter to break the line
   *        - If some part is too big to fit, it lets it be.
   * If the code string or a part doesn't exceed the max length
   *        - Replaces the delimiter with space.
   *
   * @param delimiter e.g "\t" The function respects all other chars except
   *                  the delimiter which it always replaces
   *                  either with " " or "\n".
   */
  final public function addWithSuggestedLineBreaks(
    ?string $code,
    ...
  ): this {
    if ($code === null) {
      return $this;
    }

    // If there's more than 1 line, add them 1 by 1
    $lines = Str::explode("\n", $code);
    if ($lines->count() > 1) {
      // The last line shouldn't have a finishing end line,
      // so add it manually
      $last_line = $lines->pop();
      $this->addLinesWithSuggestedLineBreaks($lines);
      return $this->addWithSuggestedLineBreaks($last_line);
    }

    $args = array_slice(func_get_args(), 1);
    if (count($args)) {
      $code = vsprintf($code, $args);
    }
    // Subtracting two to allow space for indenting and/or opening braces.
    $max_length = $this->getMaxCodeLength() - 2;
    if ($this->isInsideFunction) {
      // Please note that this method is called both from inside a function or
      // stuff like class/func declaration.
      $max_length = $max_length - 2;
    }

    $lines_with_sugg_breaks = explode(self::DELIMITER, $code);
    $final_lines = Vector {};
    foreach ($lines_with_sugg_breaks as $line) {
      if (!$line) {
        // Continue if the line is empty
        continue;
      }
      invariant(is_string($line), 'For Hack');
      if ($final_lines->isEmpty()) {
        $final_lines->add($line);
      } else {
        $last_line = $final_lines->pop();
        $composite_line = $last_line . ' ' . $line;
        if (strlen($composite_line) > $max_length) {
          $final_lines->add($last_line)->add($line);
        } else {
          // Concatenate the line to the last line
          $final_lines->add($composite_line);
        }
      }
    }
    return $this->add(implode("\n  ", $final_lines->toArray()));
  }

  /**
   * Similar to addWithSuggestedLineBreaks but allows to add more than one
   * line at a time.  See that method for more information.
   */
  final public function addLinesWithSuggestedLineBreaks(
    Vector<string> $lines,
  ): this {
    foreach ($lines as $line) {
      $this->addWithSuggestedLineBreaks($line)->newLine();
    }
    return $this;
  }

  final protected function setIndentationLevel(int $level): void {
    $this->indentationLevel = $level;
  }

  final protected static function checkIfLineIsTooLong(
    string $code,
    int $max_length,
  ): bool {
    $line_too_long = false;
    foreach (explode("\n", $code) as $line) {
      if (strlen($line) > $max_length) {
        $line_too_long = true;
        break;
      }
    }
    return $line_too_long;
  }

  /**
   * Returns the maximum length of code based on the current identation level.
   */
  final protected function getMaxCodeLength(): int {
    return $this->config->getMaxLineLength() -
      $this->config->getSpacesPerIndentation() * $this->indentationLevel;
  }

  final public function indent(): this {
    $this->indentationLevel++;
    return $this;
  }

  final public function unindent(): this {
    invariant(
      $this->indentationLevel >= 1,
      'Indentation level cannot go below zero.',
    );
    $this->indentationLevel--;
    return $this;
  }

  /**
   * Get the code that was inserted in the buffer.
   */
  final public function getCode(): string {
    invariant(
      !$this->wasGetCodeCalled,
      "You may only call getCode() once on a given HackBuilder object.",
    );
    $this->wasGetCodeCalled = true;
    return $this->code->detach();
  }

  /**
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
  public function beginManualSection(string $id): this {
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
