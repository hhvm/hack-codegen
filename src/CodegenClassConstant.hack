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
 * Generate code for a class constant.
 *
 * @see IHackCodegenFactory::codegenConstant
 */
final class CodegenClassConstant extends CodegenConstantish {
  use HackBuilderRenderer;

  public function __construct(
    protected IHackCodegenConfig $config,
    string $name,
  ) {
    parent::__construct($config, $name);
  }

  private bool $isAbstract = false;

  public function isAbstract(): bool {
    return $this->isAbstract;
  }

  public function setIsAbstract(bool $value): this {
    $this->isAbstract = $value;
    return $this;
  }

  private ?string $type;

  public function getType(): ?string {
    return $this->type;
  }

  /** @selfdocumenting */
  public function setType(string $type): this {
    $this->type = $type;
    return $this;
  }

  /** Set the type of the constant using a %-placeholder format string */
  public function setTypef(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): this {
    return $this->setType(\vsprintf($format, $args));
  }

  <<__Override>>
  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    $value = $this->getValue();
    $abstract = $this->isAbstract();
    invariant(
      ($value !== null) !== $abstract,
      'class constants must either be abstract or have a value',
    );
    $type = $this->getType();
    return $builder
      ->addDocBlock($this->getDocBlock())
      ->ensureNewLine()
      ->addIf($abstract, 'abstract ')
      ->add('const ')
      ->addIf($type !== null, ($type ?? '').' ')
      ->add($this->getName())
      ->addIf($value !== null, ' = '.($value ?? ''))
      ->addLine(';');
  }
}
