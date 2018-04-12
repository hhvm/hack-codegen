<?hh // strict
/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

final class CodegenConstant implements ICodeBuilderRenderer {
  use HackBuilderRenderer;

  private ?string $comment;
  private ?string $type;
  private ?string $value = null;

  public function __construct(
    protected IHackCodegenConfig $config,
    private string $name,
  ) {
  }

  public function getName(): string {
    return $this->name;
  }

  public function getType(): ?string {
    return $this->type;
  }

  public function getValue(): mixed {
    return $this->value;
  }

  public function setDocBlock(string $comment): this {
    $this->comment = $comment;
    return $this;
  }

  public function setType(string $type): this {
    $this->type = $type;
    return $this;
  }

  public function setTypef(SprintfFormatString $format, mixed ...$args): this {
    return $this->setType(\vsprintf($format, $args));
  }

  public function setValue<T>(
      T $value,
      IHackBuilderValueRenderer<T> $renderer,
  ): this {
    $this->value = $renderer->render($this->config, $value);
    return $this;
  }

  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    $value = $this->value;
    invariant(
      $value !== null,
      'constants must have a value',
    );
    return $builder
      ->addDocBlock($this->comment)
      ->ensureNewLine()
      ->add('const ')
      ->addIf($this->type !== null, $this->type.' ')
      ->add($this->name)
      ->addIf($value !== null, ' = '.$value)
      ->addLine(';');
  }
}
