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
 * Describes how the code was generated in order to write a comment on
 * the generated file.
 * Use one of the helper functions below (codegen_generated_from_*). E.g.
 *
 *   $generated_from =  codegen_generated_from_script();
 *   $file = codegen_file('file.php')
 *     ->setGeneratedFrom($generated_from);
 *
 */
final class CodegenGeneratedFrom implements ICodeBuilderRenderer {
  use HackBuilderRenderer;

  public function __construct(private string $msg) {}

  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    return $builder->add($this->msg);
  }
}

function codegen_generated_from_class(string $class): CodegenGeneratedFrom {
  return new CodegenGeneratedFrom("Generated from $class");
}

function codegen_generated_from_string(string $string): CodegenGeneratedFrom {
  return new CodegenGeneratedFrom("Generated from $string");
}

function codegen_generated_from_method(
  string $class,
  string $method
): CodegenGeneratedFrom {
  return new CodegenGeneratedFrom("Generated from $class::$method()");
}

function codegen_generated_from_method_with_key(
  string $class,
  string $method,
  string $key
): CodegenGeneratedFrom {
  return new CodegenGeneratedFrom("Generated from $class::$method()['$key']");
}

function codegen_generated_from_script(
  ?string $script = null
): CodegenGeneratedFrom {
  if ($script === null) {
    $trace = debug_backtrace();
    $last = end($trace);
    invariant(
      $last !== false,
      "Couldn't get the strack trace.  Please pass the script name to ".
      "codegen_generated_from_script",
    );
    $script = codegen_file($last['file'])->getRelativeFileName();
  }
  return new CodegenGeneratedFrom("To re-generate this file run $script");
}
