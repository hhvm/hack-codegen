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

enum ContainerType: string {
  PHP_ARRAY = 'array';
  DICT = 'dict';
  VEC = 'vec';
  KEYSET = 'keyset';
  MAP = 'Map';
  IMM_MAP = 'ImmMap';
  VECTOR = 'Vector';
  IMM_VECTOR = 'ImmVector';
  SET = 'Set';
  IMM_SET = 'ImmSet';
  SHAPE_TYPE = 'shape';
}

/**
 * Class to facilitate building code. It has methods for some common patterns
 * used to generate code. It also deals with indentation and new lines.
 */
final class HackBuilder extends BaseCodeBuilder {

  /**
   * This method lets you call Multiline methods and also allows you to
   * suggest line breaks. It first tries to fit the call in a single line, then
   * by breaking at suggested line breaks.
   * If these don't materialize, it falls back to multi line calling and
   * uses suggested line breaks for each line individually.
   *
   * One more thing that is different from vanilla Multilinecall is the
   * parameter which allows you to tell the function if closeStatement has
   * to be included in the call. This is important as it changes the way
   * we return code.
   */
  public function addMultilineCall(
    string $func_call_line,
    Traversable<string> $params,
    bool $include_close_statement = true,
  ): this {
    // Mark that the call is inside a function
    $this->setIsInsideFunction();
    // Get the max_length. Substracting 4 as Multiline call happens inside a
    // method.
    $max_length = $this->getMaxCodeLength() - 4;

    // Let's put everything in a single line
    $args = '('.Str\join($params, ', ').')';
    $composite_line = $func_call_line.$args;
    // Ignore suggested line breaks within individual args; otherwise we could
    // split in the middle of arguments rather than after each parameter.
    $composite_line_no_breaks =
      $func_call_line.\str_replace(self::DELIMITER, ' ', $args);
    if ($include_close_statement) {
      $composite_line = $composite_line.";\n";
      $composite_line_no_breaks = $composite_line_no_breaks.";\n";
    }
    $clone_builder = $this->getClone();
    $clone_builder->addWithSuggestedLineBreaks($composite_line_no_breaks);

    if (!self::checkIfLineIsTooLong($clone_builder->getCode(), $max_length)) {
      return $this->addWithSuggestedLineBreaks($composite_line);
    }

    $this
      ->addWithSuggestedLineBreaks($func_call_line.'(')
      ->newLine()
      ->indent()
      ->addLinesWithSuggestedLineBreaks(Vec\map($params, $line ==> $line.','))
      ->unindent()
      ->add(')');
    if ($include_close_statement) {
      $this->closeStatement();
    }
    return $this;
  }

  public function addValue<T>(T $value, IHackBuilderValueRenderer<T> $r): this {
    return $this->add($r->render($this->config, $value));
  }

  /**
   * Add a string that is auto-wrapped to not exceed the maximum length.
   * The following lines will have a level of indentation added. Example:
   *
   * return 'First line of the long code'.
   *   'Second line of the long code';
   */
  public function addWrappedString(
    string $line,
    ?int $max_length = null,
  ): this {
    return $this->addWrappedStringImpl($line, $max_length, true);
  }

  /**
   * Add a string that is auto-wrapped to not exceed the maximum length.
   * The following lines will have the same level of indentation as the first
   * one. Example:
   *
   * $this->callMethod(
   *   'First line of the long code'.
   *   'Second line of the long code'
   * );
   */
  public function addWrappedStringNoIndent(
    string $line,
    ?int $max_length = null,
  ): this {
    return $this->addWrappedStringImpl($line, $max_length, false);
  }

  private function addWrappedStringImpl(
    string $line,
    ?int $max_length = null,
    bool $indent_non_first_lines = true,
  ): this {
    $max_length = $max_length !== null
      ? $max_length
      : // subtract 3 for the two quotes and . operator
        $this->getMaxCodeLength() - 3;

    $lines = $this->splitString($line, $max_length, /*preserve_space*/ true);
    if (!$lines) {
      return $this;
    }

    $this->add(_Private\normalized_var_export(C\first($lines)));
    if (C\count($lines) === 1) {
      return $this;
    }

    // If we have multiple line segments to add, add all the
    // inbetween ones with the concat operator
    $this->add('.')->newLine();

    if ($indent_non_first_lines) {
      $this->indent();
    }

    $lines
      |> Vec\slice($$, 1, C\count($lines) - 2)
      |> Vec\map(
        $$,
        $line ==> $this->addLine(_Private\normalized_var_export($line).'.'),
      );
    // And then add the last
    $this->add(_Private\normalized_var_export(C\last($lines)));
    if ($indent_non_first_lines) {
      $this->unindent();
    }
    return $this;
  }

  public function addReturn<T>(
    T $value,
    IHackBuilderValueRenderer<T> $renderer,
  ): this {
    return $this->add('return ')->addValue($value, $renderer)->addLine(';');
  }
  public function addReturnf(
    Str\SprintfFormatString $value,
    mixed ...$args
  ): this {
    return
      $this->addReturn(\vsprintf($value, $args), HackBuilderValues::literal());
  }
  public function addReturnVoid(): this{
    return $this->addLine('return;');
  }

  public function addAssignment<T>(
    string $var_name,
    T $value,
    IHackBuilderValueRenderer<T> $renderer,
  ): this {
    return $this->addWithSuggestedLineBreaksf(
      "%s =\0%s;\n",
      $var_name,
      $renderer->render($this->config, $value),
    );
  }

  /**
   * Open a brace in the current line and start a new line
   * with one more level of indentation.
   */
  public function openBrace(): this {
    return $this->addLine(' {')->indent();
  }

  public function openContainer(ContainerType $type): this {
    switch ($type) {
      case ContainerType::DICT:
      case ContainerType::KEYSET:
      case ContainerType::VEC:
        $container_sign = '[';
        break;
      case ContainerType::IMM_MAP:
      case ContainerType::IMM_SET:
      case ContainerType::IMM_VECTOR:
      case ContainerType::MAP:
      case ContainerType::SET:
      case ContainerType::VECTOR:
        $container_sign = ' {';
        break;
      case ContainerType::SHAPE_TYPE:
      case ContainerType::PHP_ARRAY:
        $container_sign = '(';
        break;
    }
    return $this->addLine(((string)$type).$container_sign)->indent();
  }

  /**
   * Close a brace in a new line and sets one less level of indentation.
   */
  public function closeBrace(): this {
    return $this->ensureNewLine()->unindent()->addLine('}');
  }

  public function closeContainer(ContainerType $type): this {
    switch ($type) {
      case ContainerType::DICT:
      case ContainerType::KEYSET:
      case ContainerType::VEC:
        $container_sign = ']';
        break;
      case ContainerType::IMM_MAP:
      case ContainerType::IMM_SET:
      case ContainerType::IMM_VECTOR:
      case ContainerType::MAP:
      case ContainerType::SET:
      case ContainerType::VECTOR:
        $container_sign = '}';
        break;
      case ContainerType::SHAPE_TYPE:
      case ContainerType::PHP_ARRAY:
        $container_sign = ')';
        break;
    }
    return $this->unindent()->add($container_sign);
  }

  public function closeStatement(): this {
    return $this->addLine(';');
  }

  /**
   * Start a if block, put the condition between the parenthesis, then
   * it's equivalent to calling openBrace, which newline and indent.
   * startIfBlock('$a === 0') generates if ($a === 0) {\n
   */
  public function startIfBlock(string $condition): this {
    return $this->add('if (')->add($condition)->add(')')->openBrace();
  }

  public function startIfBlockf(
    Str\SprintfFormatString $condition,
    mixed ...$args
  ): this {
    return $this->startIfBlock(\vsprintf($condition, $args));
  }

  /**
   * Strictly equivalent to calling closeBrace, which unindent and newline,
   * but for readability, you should use this with startIfBlock
   */
  public function endIfBlock(): this {
    return $this->closeBrace();
  }

  /**
   * End current if/else block, and start a 'else if (condition)' block
   */
  public function addElseIfBlock(string $condition): this {
    return $this
      ->ensureNewLine()
      ->unindent()
      ->add('} else ')
      ->startIfBlock($condition);
  }

  public function addElseIfBlockf(
    Str\SprintfFormatString $condition,
    mixed ...$args
  ): this {
    return $this->addElseIfBlock(\vsprintf($condition, $args));
  }

  /**
   * End current if/else block, and start a else block
   */
  public function addElseBlock(): this {
    return $this->ensureNewLine()->unindent()->add('} else')->openBrace();
  }

  /**
   * Start a foreach loop, generate the temporary variable assignement, then
   * it's equivalent to calling openBrace, which newline and indent.
   *
   * @param $traversable: the traversable object to iterate
   * @param $key: if provided, the name of the key variable
   * @param $value: the name of the value temporary variable
   *
   * startForeachLoop('$values', null, '$value') generates:
   *   foreach ($values as $value) {\n
   * startForeachLoop('self::getAll()', '$arr', '$idx') generates:
   *   foreach (self::getAll() as $idx => $arr) {\n
   */
  public function startForeachLoop(
    string $traversable,
    ?string $key,
    string $value,
  ): this {
    $this->assertIsVariable($key !== null ? $key : '$_');
    $this->assertIsVariable($value);
    return $this
      ->addWithSuggestedLineBreaksf(
        'foreach (%s as%s%s%s)',
        $traversable,
        self::DELIMITER,
        $key !== null ? \sprintf('%s => ', $key) : '',
        $value,
      )
      ->openBrace();
  }

  /**
   * Strictly equivalent to calling closeBrace, which unindent and newline,
   * but for readability, you should use this with startForeach
   */
  public function endForeachLoop(): this {
    return $this->closeBrace();
  }

  /**
   * Starts building a switch-statement that can loop over an Iterable
   * to build each case-statement
   *
   * example:
   *
   * hack_builder()
   *   ->startSwitch('$soccer_player')
   *   ->addCaseBlocks(
   *     $players,
   *     ($player, $body) ==> {
   *       $body->addCase($player['name'])
   *         ->addLine('$shot = new Shot(\''.$player['favorite_shot'].'\');')
   *         ->returnCase('$shot->execute()');
   *     },
   *   )
   *   ->addDefault()
   *   ->addLine('invariant_violation(\'ball deflated!\');')
   *   ->endDefault()
   *   ->endSwitch();
   *
   */
  public function startSwitch(string $condition): this {
    return $this->addLinef('switch (%s) {', $condition)->indent();
  }

  public function addCaseBlocks<T>(
    Traversable<T> $switch_values,
    (function(T, HackBuilder): void) $func,
  ): this {
    foreach ($switch_values as $v) {
      $func($v, $this);
    }
    return $this;
  }

  public function addCase<T>(
    T $case,
    IHackBuilderValueRenderer<T> $formatter,
  ): this {
    return $this->addLinef('case %s:', $formatter->render($this->config, $case))
      ->indent();
  }

  public function addDefault(): this {
    return $this->addLine('default:')->indent();
  }

  public function endDefault(): this {
    return $this->unindent();
  }

  public function returnCase<T>(
    T $value,
    IHackBuilderValueRenderer<T> $r,
  ): this {
    return $this->addReturn($value, $r)->unindent();
  }

  public function returnCasef(
    Str\SprintfFormatString $value,
    mixed ...$args
  ): this {
    return
      $this->returnCase(\vsprintf($value, $args), HackBuilderValues::literal());
  }

  public function breakCase(): this {
    return $this->addLine('break;')->unindent();
  }

  public function endSwitch(): this {
    return $this->closeBrace();
  }

  /**
   * Start try-catch-finally blocks in the code.
   * Very similar to startIfBlock, this is mostly a sugar on openBrace
   * to make the code more meaningful.
   *
   * Example:
   * hack_builder()
   *   ->startTryBlock()
   *   ->addLine('my_func();')
   *   ->addCatchBlock('SystemException', '$ex')
   *   ->addLine('return null;')
   *   ->addFinallyBlock()
   *   ->addLine('bump_ods();')
   *   ->endTryBlock()
   */
  public function startTryBlock(): this {
    return $this->add('try')->openBrace();
  }

  /**
   * Start a catch block.
   * @param $class: the class name of the exception
   * @param $variable: the variable name for the exception instance
   */
  public function addCatchBlock(string $class, string $variable): this {
    $this->assertIsVariable($variable);
    return $this
      ->ensureNewLine()
      ->unindent()
      ->addf('} catch (%s %s)', $class, $variable)
      ->openBrace();
  }

  /**
   * Start a finally block.
   */
  public function addFinallyBlock(): this {
    return $this->ensureNewLine()->unindent()->add('} finally')->openBrace();
  }

  /**
   * Strictly equivalent to calling closeBrace, which unindent and newline,
   * but for readability, you should use this with startTryBlock
   */
  public function endTryBlock(): this {
    return $this->closeBrace();
  }

  /**
   * Add a //-style comment
   */
  public function addInlineComment(?string $comment): this {
    if ($comment === null) {
      return $this;
    }
    // Max length of each line of the docblock.  Subtract 3 to compensate
    // for the initial "// "
    $max_length = $this->getMaxCodeLength() - 3;
    $lines = $this->splitString($comment, $max_length);
    foreach ($lines as $line) {
      $this->addLine(\rtrim('// '.$line));
    }
    return $this;
  }

  /**
   * Add a /*-style comment. You probably don't want to do this instead
   * of adding a docBlock or a //-style comment, but HH_FIXME requires
   * the star format soooooo here we are.
   */
  public function addInlineCommentWithStars(?string $comment): this {
    if ($comment === null) {
      return $this;
    }
    // Max length of each line of the docblock.  Subtract 6 to compensate
    // for the initial and trailing "/* " and " */"
    $max_length = $this->getMaxCodeLength() - 6;
    $lines = $this->splitString($comment, $max_length);
    foreach ($lines as $line) {
      $this->addLine('/* '.\rtrim($line).' */');
    }
    return $this;
  }

  /**
   * Add a Doc Block in the buffer.  You just need to pass the text of the
   * comment inside.  It will take care of the indentation and splitting long
   * lines.  You can use line breaks in the comment.
   */
  public function addDocBlock(?string $comment, ?int $max_length = null): this {
    if ($comment === '' || $comment === null) {
      return $this;
    }
    // Max length of each line of the docblock.  Substract 3 to compensate
    // for the initial " * "
    $max_length =
      $max_length !== null ? $max_length : $this->getMaxCodeLength() - 3;

    $lines = $this->splitString($comment, $max_length);
    $this->ensureNewLine()->addLine('/**');
    foreach ($lines as $line) {
      $this->addLine(\rtrim(' * '.$line));
    }
    $this->addLine(' */');
    return $this;
  }

  /**
   * Split a string on lines of at most $maxlen length.  Line breaks in
   * the string will be respected.
   */
  private function splitString(
    string $str,
    int $maxlen,
    bool $preserve_space = false,
  ): vec<string> {
    $lines = vec[];
    $src_lines = \explode("\n", $str);

    foreach ($src_lines as $src_line) {
      while (Str\length($src_line) > $maxlen) {
        $last_space = \strrpos(\substr($src_line, 0, $maxlen), ' ');
        if ($last_space === false) {
          break;
        }
        if ($preserve_space) {
          $lines[] = \substr($src_line, 0, $last_space + 1);
        } else {
          $lines[] = \substr($src_line, 0, $last_space);
        }
        $src_line = \substr($src_line, $last_space + 1);
      }
      $lines[] = $src_line;
    }

    return $lines;
  }

  public static function multilineCall(
    IHackCodegenConfig $config,
    string $name,
    Traversable<string> $params,
    bool $close_statement = false,
  ): string {
    return (new HackBuilder($config))
      ->addSimpleMultilineCall($name, $params)
      ->addIf($close_statement, ';')
      ->getCode();
  }

  /**
   * Used in static function multilineCall where we don't know the current
   * indentation to wrap code correctly.
   * Adds a call (method, function, array construction, etc) where each param
   * is in one separate line.  It's enclosed in parens.  Trailing commas
   * are added to the params lines.
   */
  private function addSimpleMultilineCall(
    string $name,
    Traversable<string> $params,
  ): this {
    return $this
      ->addLine($name.'(')
      ->indent()
      ->addLines(Vec\map($params, $line ==> $line.','))
      ->unindent()
      ->add(')');
  }

  public function addRenderer(ICodeBuilderRenderer $renderer): this {
    $renderer->appendToBuilder($this);
    return $this;
  }

  private function assertIsVariable(string $name): void {
    invariant(
      Str\starts_with($name, '$'),
      'Expecting a variable name, but "%s" is not valid.',
      $name,
    );
  }
}
