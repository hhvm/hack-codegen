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
 * Describe an used trait, optionally including a comment, like:
 *
 * // Generated from CowSchema::Moo()
 * use MooInterface;
 *
 * Use the methods codegen_uses_trait[s] to instantiate it. E.g.:
 *
 * $trait = codegen_uses_trait('TFoo')
 *   ->setComment('Some common foo methods');
 * $class = codegen_class('MyClass')
 *   ->addTrait($trait);
 */
final class CodegenUsesTrait {

  private ?string $comment;

  public function __construct(private string $name) {}

  public function getName(): string {
    return $this->name;
  }

  public function setComment(string $format, ...): this {
    $comment = vsprintf($format, array_slice(func_get_args(), 1));
    $this->comment = $comment;
    return $this;
  }

  public function setGeneratedFrom(
    CodegenGeneratedFrom $from
  ): this {
    $this->setComment($from->render());
    return $this;
  }

  public function render(): string {
    return hack_builder()
      ->addInlineComment($this->comment)
      ->addLine("use %s;", $this->name)
      ->getCode();
  }
}

function codegen_uses_trait(string $name): CodegenUsesTrait {
  return new CodegenUsesTrait($name);
}

function codegen_uses_traits(
  Vector<string> $names,
): Vector<CodegenUsesTrait> {
  return $names->map($x ==> codegen_uses_trait($x));
}
