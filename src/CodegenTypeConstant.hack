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
 * Generate code for a class type constant.
 *
 * @see IHackCodegenFactory::codegenConstant
 */
final class CodegenTypeConstant
  extends CodegenConstantish
  implements ICodeBuilderRenderer {
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

  private ?string $constraint;
  public function getConstraint(): ?string {
    return $this->constraint;
  }

  public function setConstraint(string $c): this {
    $this->constraint = $c;
    return $this;
  }

  public function setConstraintf(
    Str\SprintfFormatString $c,
    mixed ...$args
  ): this {
    $this->constraint = \vsprintf($c, $args);
    return $this;
  }

  <<__Override>>
  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    $value = $this->getValue();
    $abstract = $this->isAbstract();
    $constraint = $this->getConstraint();
    invariant(
      ($value !== null) !== $abstract,
      'type constants must either be abstract or have a value',
    );
    return $builder
      ->addDocBlock($this->getDocBlock())
      ->ensureNewLine()
      ->addIf($abstract, 'abstract ')
      ->add('const type ')
      ->add($this->getName())
      ->addIf($constraint !== null, ' '.($constraint ?? ''))
      ->addIf($value !== null, ' = '.($value ?? ''))
      ->addLine(';');
  }
}
