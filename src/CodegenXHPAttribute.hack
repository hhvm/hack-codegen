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
 * Generate code for an xhp attribute. Please don't use this class directly;
 * instead use the function ICodegenFactory->codegenAttribute.  E.g.:
 *
 * ICodegenFactory->codegenAttribute('src')
 *  ->setType('string')
 *  ->setInlineComment('A script src must be a valid URI')
 *  ->render();
 */
final class CodegenXHPAttribute implements ICodeBuilderRenderer {

  use HackBuilderRenderer;

  private ?string $comment;
  private ?string $type;
  private ?string $value;
  private ?XHPAttributeDecorator $decorator;

  public function __construct(
    protected IHackCodegenConfig $config,
    private string $name,
  ) {}

  public function getName(): string {
    return $this->name;
  }

  public function getType(): ?string {
    return $this->type;
  }

  public function getValue(): mixed {
    return $this->value;
  }

  public function setDecorator(?XHPAttributeDecorator $decorator): this {
    invariant(
      $decorator is null || $this->value is null,
      'XHP attributes with a default value can not have an %s decorator',
      xhp_attribute_decorator_to_string($decorator),
    );
    $this->decorator = $decorator;
    return $this;
  }

  public function setInlineComment(string $comment): this {
    $this->comment = $comment;
    return $this;
  }

  /**
   * Set the type of the member var.  In Hack, if it's nullable
   * you should prepend the question mark, e.g. "?string".
   * XHP enums should be avoided, but you can specify "enum { 'foo' }"
   * as a literal string if you need it.
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
    invariant(
      $this->decorator is null,
      'XHP attributes with an %s decorator can not have a default value',
      xhp_attribute_decorator_to_string($this->decorator),
    );
    $this->value = $renderer->render($this->config, $value);
    return $this;
  }

  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    $value = $this->value;

    return $builder
      ->addDocBlock($this->comment)
      ->addIf($this->type is nonnull, ($this->type ?? '').' ')
      ->add($this->name)
      ->addIf($this->value is nonnull, ' = '.($value ?? ''))
      ->addIf(
        $this->decorator is nonnull,
        ' '.
        xhp_attribute_decorator_to_string(
          $this->decorator ?? XHPAttributeDecorator::REQUIRED,
        ),
      );
  }

}
