/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

/**
 * Generate code for a constant that is not part of a class.
 *
 * @see IHackCodegenFactory::codegenConstant
 */
abstract class CodegenConstantish implements ICodeBuilderRenderer {
  private ?string $comment;
  private ?string $value = null;

  /** @selfdocumenting */
  public function __construct(
    protected IHackCodegenConfig $config,
    private string $name,
  ) {
  }

  /** @selfdocumenting */
  public function getName(): string {
    return $this->name;
  }

  /** Returns the value as code */
  public function getValue(): ?string {
    return $this->value;
  }

  /** @selfDocumenting */
  public function getDocBlock(): ?string {
    return $this->comment;
  }

  /** @selfdocumenting */
  public function setDocBlock(string $comment): this {
    $this->comment = $comment;
    return $this;
  }

  /**
   * Set the value of the constant using a renderer.
   *
   * @param $renderer a renderer for the value. In general, this should be
   *   created using `HackBuilderValues`
   */
  public function setValue<T>(
    T $value,
    IHackBuilderValueRenderer<T> $renderer,
  ): this {
    $this->value = $renderer->render($this->config, $value);
    return $this;
  }
}
