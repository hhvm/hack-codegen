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
 * Describes an implemented interface, optionally including a comment, like:
 *
 * // Generated from CowSchema::Moo()
 * IDoesMoo
 *
 * Use the methods codegen_implements_interface[s] to instantiate it. E.g.:
 *
 * $i = codegen_implements_interface('IUser')
 *   ->setComment('Some kind of user');
 * $class = codegen_class('MyClass')
 *   ->addInterface($i);
 */
final class CodegenImplementsInterface implements ICodeBuilderRenderer {

  use HackBuilderRenderer;

  private ?string $comment;

  public function __construct(
    protected IHackCodegenConfig $config,
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

  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    return $builder
      ->indent()
      ->addInlineComment($this->comment)
      ->add($this->name)
      ->unindent();
  }
}
