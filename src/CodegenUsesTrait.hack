/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use namespace HH\Lib\Str;

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

  public function __construct(
    private IHackCodegenConfig $config,
    private string $name,
  ) {
  }

  public function getName(): string {
    return $this->name;
  }

  public function setComment(string $comment): this {
    $this->comment = $comment;
    return $this;
  }

  public function setCommentf(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): this {
    return $this->setComment(\vsprintf($format, $args));
  }

  public function setGeneratedFrom(CodegenGeneratedFrom $from): this {
    $this->setComment($from->render());
    return $this;
  }

  public function render(): string {
    return (new HackBuilder($this->config))
      ->addInlineComment($this->comment)
      ->addLinef('use %s;', $this->name)
      ->getCode();
  }
}
