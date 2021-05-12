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
 * Generate code for a property variable. Please don't use this class directly;
 * instead use the function codegen_property.  E.g.:
 *
 * codegen_property('foo')
 *  ->setProtected()
 *  ->setType('string')
 *  ->setInlineComment('Represent the foo of the bar")
 *  ->render();
 */
final class CodegenProperty implements ICodeBuilderRenderer {

  use CodegenWithVisibility;
  use HackBuilderRenderer;

  private ?string $comment;
  private ?string $type;
  private ?string $value;
  private bool $isStatic = false;

  public function __construct(
    protected IHackCodegenConfig $config,
    private string $name,
  ) {
    // Private by default
    $this->setPrivate();
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

  public function setInlineComment(string $comment): this {
    $this->comment = $comment;
    return $this;
  }

  public function setIsStatic(bool $value = true): this {
    $this->isStatic = $value;
    return $this;
  }

  /**
   * Set the type of the member var.  In Hack, if it's nullable
   * you should prepend the question mark, e.g. "?string".
   */
  public function setType(string $type): this {
    $this->type = $type;
    return $this;
  }

  public function setTypef(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): this {
    return $this->setType(\vsprintf($format, $args));
  }

  /**
   * Set the initial value for the variable.  You can pass numbers, strings,
   * arrays, etc, and it will generate the code to render those values.
   */
  public function setValue<T>(
    T $value,
    IHackBuilderValueRenderer<T> $renderer,
  ): this {
    $this->value = $renderer->render($this->config, $value);
    return $this;
  }

  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    $value = $this->value;

    return $builder
      ->addInlineComment($this->comment)
      ->add($this->getVisibility().' ')
      ->addIf($this->isStatic, 'static ')
      ->addIf($this->type !== null, ($this->type ?? '').' ')
      ->add('$'.$this->name)
      ->addIf($this->value !== null, ' = '.($value ?? ''))
      ->addLine(';');
  }

}
