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
    Vector<string> $params,
    bool $include_close_statement = true,
  ): this {
    // Mark that the call is inside a function
    $this->setIsInsideFunction();
    // Get the max_length. Substracting 4 as Multiline call happens inside a
    // method.
    $max_length = $this->getMaxCodeLength() - 4;

    // Let's put everything in a single line
    $args = '('.implode(', ', $params->toArray()).')';
    $composite_line = $func_call_line . $args;
    // Ignore suggested line breaks within individual args; otherwise we could
    // split in the middle of arguments rather than after each parameter.
    $composite_line_no_breaks = $func_call_line.
      str_replace(self::DELIMITER, ' ', $args);
    if ($include_close_statement) {
      $composite_line = $composite_line . ";\n";
      $composite_line_no_breaks = $composite_line_no_breaks . ";\n";
    }
    $clone_builder = $this->getClone();
    $clone_builder->addWithSuggestedLineBreaks($composite_line_no_breaks);

    if (!self::checkIfLineIsTooLong($clone_builder->getCode(), $max_length)) {
      return $this->addWithSuggestedLineBreaks($composite_line);
    }

    $this->addWithSuggestedLineBreaks("$func_call_line(")
     ->newLine()
     ->indent()
     ->addLinesWithSuggestedLineBreaks(
         $params->map(function(string $line) { return $line.','; })
       )
      ->unindent()
      ->add(')');
    if ($include_close_statement) {
      $this->closeStatement();
    }
    return $this;
  }

  /**
   * Add a Map with the specified pairs.
   *
   * @param ConstMap<Tk, Tv> $map The Map or Map to output. Note that
   *    regardless of which type is passed in, a Map will be output. This is
   *    useful for sorting the map for readability, as long as you don't need
   *    the sorting in actual code.
   */
  public function addMap<Tk, Tv>(
    \ConstMap<Tk, Tv> $map,
    HackBuilderKeys $keys_config = HackBuilderKeys::EXPORT,
    HackBuilderValues $values_config = HackBuilderValues::EXPORT,
  ): this {
    return $this->addMapHelper(
      'Map',
      $map,
      $keys_config,
      $values_config,
    );
  }

  /**
   * Add an ImmMap with the specified pairs.
   *
   * @param ConstMap<Tk, Tv> $map The Map or Map to output. Note that
   *    regardless of which type is passed in, an ImmMap will be output. This is
   *    useful for sorting the map for readability, as long as you don't need
   *    the sorting in actual code.
   */
  public function addImmMap<Tk, Tv>(
    \ConstMap<Tk, Tv> $map,
    HackBuilderKeys $keys_config = HackBuilderKeys::EXPORT,
    HackBuilderValues $values_config = HackBuilderValues::EXPORT,
  ): this {
    return $this->addMapHelper(
      'ImmMap',
      $map,
      $keys_config,
      $values_config,
    );
  }

  private function addMapHelper<Tk, Tv>(
    string $type,
    \ConstMap<Tk, Tv> $map,
    HackBuilderKeys $keys_config = HackBuilderKeys::EXPORT,
    HackBuilderValues $values_config = HackBuilderValues::EXPORT,
  ): this {
    // Sort the map to make sure that it will always be represented in the
    // same way.  This is to avoid the code to be re-written just with a
    // different order that doesn't actually change anything.
    $array = $map->toArray();
    ksort($array);
    return $this
      ->add($type)->openBrace()
      ->addArrayKeysAndValues(
        $array,
        $keys_config,
        $values_config,
      )
      ->unindent()->add('}');
  }


  /**
   * Add a Vector with the specified elements.
   */
  public function addVector<T>(
    \ConstVector<T> $vector,
    HackBuilderValues $values_config = HackBuilderValues::EXPORT,
  ): this {
    return $this
      ->add('Vector')->openBrace()
      ->addArrayValues($vector->toArray(), $values_config)
      ->unindent()->add('}');
  }

  /**
   * Add an ImmVector with the specified elements.
   */
  public function addImmVector<T>(
    \ConstVector<T> $vector,
    HackBuilderValues $values_config = HackBuilderValues::EXPORT,
  ): this {
    return $this
      ->add('ImmVector')->openBrace()
      ->addArrayValues($vector->toArray(), $values_config)
      ->unindent()->add('}');
  }

  /**
   * Add a Set with the specified elements.
   */
  public function addSet<T>(
    \ConstSet<T> $set,
    HackBuilderValues $values_config = HackBuilderValues::EXPORT,
  ): this {
    return $this
      ->add('Set')->openBrace()
      ->addArrayValues($set->toArray(), $values_config)
      ->unindent()->add('}');
  }

  /**
   * Add an ImmSet with the specified elements.
   */
  public function addImmSet<T>(
    \ConstSet<T> $set,
    HackBuilderValues $values_config = HackBuilderValues::EXPORT,
  ): this {
    return $this
      ->add('ImmSet')->openBrace()
      ->addArrayValues($set->toArray(), $values_config)
      ->unindent()->add('}');
  }

  /**
   * Add an array with one element per line, vector style (no keys included).
   */
  public function addArray(
    array<mixed> $array,
    HackBuilderValues $values_config = HackBuilderValues::EXPORT,
  ): this {
    return $this
      ->addLine('array(')
      ->indent()
      ->addArrayValues($array, $values_config)
      ->unindent()
      ->add(')');
  }

  /**
   * Add an array with one element per line, map style (keys included).
   */
  public function addArrayWithKeys(
    array<mixed, mixed> $array,
    HackBuilderKeys $keys_config = HackBuilderKeys::EXPORT,
    HackBuilderValues $values_config = HackBuilderValues::EXPORT,
  ): this {
    return $this
      ->addLine('array(')
      ->indent()
      ->addArrayKeysAndValues($array, $keys_config, $values_config)
      ->unindent()
      ->add(')');
  }

  /*
   * If the value of your array is an array (list, not hashmap), renders the
   * array with keys, but the inner map without keys.
   */
  public function addArrayWithKeysAndArrayListValues(
    array<mixed, array<mixed>> $array,
    HackBuilderKeys $keys_config = HackBuilderKeys::EXPORT,
    HackBuilderValues $values_config = HackBuilderValues::EXPORT,
  ): this {
    $this
      ->addLine('array(')
      ->indent();
    $first = true;
    foreach ($array as $key => $value) {
      if (!$first) {
        $this->add(',');
      }
      $first = false;
      $rendered_key = $keys_config === HackBuilderKeys::LITERAL
        ? $key
        : $this->varExport($key);
      $this->addLine("%s =>".HackBuilder::DELIMITER, $rendered_key);
      $this->addArray($value, $values_config);
      $this->newLine();
    }
    return $this
      ->unindent()
      ->add(')');
  }

  /**
   * Add a shape construction.
   */
  public function addShape(
    array<mixed, mixed> $shape,
    HackBuilderValues $values_config = HackBuilderValues::EXPORT,
  ): this {
    return $this
      ->addLine('shape(')
      ->indent()
      ->addArrayKeysAndValues($shape, HackBuilderKeys::EXPORT, $values_config)
      ->unindent()
      ->add(')');
  }

  private function varExport(mixed $value): string {
    if ($value === null) {
      // var_export capitalizes NULL
      return 'null';
    }
    return strip_hh_prefix(var_export($value, true));
  }

  public function addVarExport(mixed $value): this {
    return $this->add($this->varExport($value));
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
    return $this->addWrappedStringImpl(
      $line,
      $max_length,
      true,
    );
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
    return $this->addWrappedStringImpl(
      $line,
      $max_length,
      false,
    );
  }

  private function addWrappedStringImpl(
    string $line,
    ?int $max_length = null,
    bool $indent_non_first_lines = true,
  ): this {
    $max_length = $max_length !== null ?
      $max_length :
      // subtract 3 for the two quotes and . operator
      $this->getMaxCodeLength() - 3;

    $lines = $this->splitString($line, $max_length, /*preserve_space*/ true);
    if (!$lines) {
      return $this;
    }

    $this->add($this->varExport($lines->firstValue()));
    if ($lines->count() === 1) {
      return $this;
    }

    // If we have multiple line segments to add, add all the
    // inbetween ones with the concat operator
    $this
      ->add('.')
      ->newLine();

    if ($indent_non_first_lines) {
      $this->indent();
    }

    $lines->slice(1, $lines->count() - 2)->map(
      $line ==> $this->addLine($this->varExport($line).'.'),
    );
    // And then add the last
    $this->add($this->varExport($lines->lastValue()));
    if ($indent_non_first_lines) {
      $this->unindent();
    }
    return $this;
  }

  private function addArrayKeysAndValues(
    array<mixed, mixed> $map,
    HackBuilderKeys $keys_config = HackBuilderKeys::EXPORT,
    HackBuilderValues $values_config = HackBuilderValues::EXPORT,
  ): this {
    foreach ($map as $key => $value) {
      $rendered_key = $keys_config === HackBuilderKeys::LITERAL
        ? $key
        : $this->varExport($key);
      $rendered_value = $values_config === HackBuilderValues::LITERAL
        ? $value
        : $this->varExport($value);
      $this->addWithSuggestedLineBreaks(
        "%s =>".HackBuilder::DELIMITER."%s,",
        $rendered_key,
        $rendered_value,
      )->newLine();
    }
    return $this;
  }

  private function addArrayValues(
    array<mixed> $list,
    HackBuilderValues $values_config = HackBuilderValues::EXPORT,
  ): this {
    foreach ($list as $value) {
      $rendered_value = $values_config === HackBuilderValues::LITERAL
        ? $value
        : $this->varExport($value);
      $this->addLine("%s,", $rendered_value);
    }
    return $this;
  }

  /* HH_FIXME[4033] variadic params with type constraints are not supported */
  public function addReturn(string $value, ...$args): this {
    return $this->addLineImpl("return $value;", $args);
  }

  public function addAssignment(string $var_name, string $value): this {
    $this->assertIsVariable($var_name);
    return $this->addLine('%s = %s;', $var_name, $value);
  }

  /**
   * Open a brace in the current line and start a new line
   * with one more level of indentation.
   */
  public function openBrace(): this {
    return $this
      ->addLine(' {')
      ->indent();
  }

  /**
   * Close a brace in a new line and sets one less level of indentation.
   */
  public function closeBrace(): this {
    return $this
      ->ensureNewLine()
      ->unindent()
      ->addLine('}');
  }

  public function closeStatement(): this {
    return $this->addLine(';');
  }

  /**
   * Start a if block, put the condition between the parenthesis, then
   * it's equivalent to calling openBrace, which newline and indent.
   * startIfBlock('$a === 0') generates if ($a === 0) {\n
   */
  /* HH_FIXME[4033] variadic params with type constraints are not supported */
  public function startIfBlock(string $condition, ...$args): this {
    return $this
      ->add('if (')
      ->addv($condition, $args)
      ->add(')')
      ->openBrace();
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
  /* HH_FIXME[4033] variadic params with type constraints are not supported */
  public function addElseIfBlock(string $condition, ...$args): this {
    return $this
      ->ensureNewLine()
      ->unindent()
      ->add('} else ')
      ->startIfBlock(vsprintf($condition, $args));
  }

  /**
   * End current if/else block, and start a else block
   */
  public function addElseBlock(): this {
    return $this
      ->ensureNewLine()
      ->unindent()
      ->add('} else')
      ->openBrace();
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
      ->addWithSuggestedLineBreaks(
        'foreach (%s as%s%s%s)',
        $traversable,
        self::DELIMITER,
        $key !== null ? sprintf('%s => ', $key) : '',
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
   *   ->endSwitch_();
   *
   */
  public function startSwitch(string $condition): this {
    return $this
      ->addLine('switch (%s) {', $condition)
      ->indent();
  }

  public function addCaseBlocks<T>(
    Iterable<T> $switch_values,
    (function (T, HackBuilder): void) $func,
  ): this {
    $switch_values->map($v ==> $func($v, $this));
    return $this;
  }

  public function addCase(string $case): this {
    return $this
      ->addLine('case %s:', $case)
      ->indent();
  }

  public function addDefault(): this {
    return $this->addLine('default:')->indent();
  }

  public function endDefault(): this {
    return $this->unindent();
  }

  public function returnCase(string $statement): this {
    return $this->addReturn($statement)->unindent();
  }

  public function breakCase(): this {
    return $this->addLine('break;')->unindent();
  }

  public function endSwitch_(): this {
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
    return $this
      ->add('try')
      ->openBrace();
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
      ->add('} catch (%s %s)', $class, $variable)
      ->openBrace();
  }

  /**
   * Start a finally block.
   */
  public function addFinallyBlock(): this {
    return $this
      ->ensureNewLine()
      ->unindent()
      ->add('} finally')
      ->openBrace();
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
      $this->addLine(rtrim('// '.$line));
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
      $this->addLine('/* '.rtrim($line).' */');
    }
    return $this;
  }

  /**
   * Add a Doc Block in the buffer.  You just need to pass the text of the
   * comment inside.  It will take care of the indentation and splitting long
   * lines.  You can use line breaks in the comment.
   */
  public function addDocBlock(
    ?string $comment,
    ?int $max_length = null,
  ): this {
    if ($comment === '' || $comment === null) {
      return $this;
    }
    // Max length of each line of the docblock.  Substract 3 to compensate
    // for the initial " * "
    $max_length = $max_length !== null ?
      $max_length :
      $this->getMaxCodeLength() - 3;

    $lines = $this->splitString($comment, $max_length);
    $this->ensureNewLine()->addLine('/**');
    foreach ($lines as $line) {
      $this->addLine(rtrim(' * ' .$line));
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
  ): Vector<string> {
    $lines = Vector {};
    $src_lines = explode("\n", $str);

    foreach ($src_lines as $src_line) {
      while (Str::len($src_line) > $maxlen) {
        $last_space = strrpos(substr($src_line, 0, $maxlen), ' ');
        if ($last_space === false) {
          break;
        }
        if ($preserve_space) {
          $lines[] = substr($src_line, 0, $last_space + 1);
        } else {
          $lines[] = substr($src_line, 0, $last_space);
        }
        $src_line = substr($src_line, $last_space + 1);
      }
      $lines[] = $src_line;
    }

    return $lines;
  }

  public static function multilineCall(
    string $name,
    Vector<string> $params,
    bool $close_statement = false
  ): string {
    return hack_builder()
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
    Vector<string> $params
  ): this {
    return $this
      ->addLine("$name(")
      ->indent()
      ->addLines($params->map(function(string $line) { return $line.','; }))
      ->unindent()
      ->add(')');
  }

  public function addRenderer(ICodeBuilderRenderer $renderer): this {
    $renderer->appendToBuilder($this);
    return $this;
  }

  private function assertIsVariable(string $name): void {
    invariant(
      Str::startsWith($name, '$'),
      'Expecting a variable name, but "%s" is not valid.',
      $name,
    );
  }
}

function hack_builder(): HackBuilder {
  return new HackBuilder(HackCodegenConfig::getInstance());
}
