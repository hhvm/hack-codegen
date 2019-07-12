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
 * Base class to generate a method or a constructor.
 *
 * @see CodegenMethod
 * @see CodegenConstructor
 */
abstract class CodegenMethodish extends CodegenFunctionish
  implements ICodeBuilderRenderer {

  use CodegenWithVisibility;

  protected bool $isAbstract = false;
  protected bool $isStatic = false;
  private bool $isFinal = false;
  private ?CodegenClassish $containingClass = null;

  public function setIsFinal(bool $value = true): this {
    $this->isFinal = $value;
    return $this;
  }

  public function setIsAbstract(bool $value = true): this {
    $this->isAbstract = $value;
    return $this;
  }

  public function setIsStatic(bool $value = true): this {
    $this->isStatic = $value;
    return $this;
  }

  public function isAbstract(): bool {
    return $this->isAbstract;
  }

  public function isStatic(): bool {
    return $this->isStatic;
  }

  public function setContainingClass(CodegenClassish $class): this {
    $this->containingClass = $class;
    if ($this->containingClass is CodegenInterface) {
      $this->isAbstract = true;
    }
    return $this;
  }

  private function getFunctionDeclaration(): string {

    // $keywords is shared by both single and multi line declaration
    $keywords = (new HackBuilder($this->config))
      ->addIf($this->isFinal && !$this->isAbstract, 'final ')
      ->addIf(
        $this->isAbstract &&
        !($this->containingClass is CodegenInterface),
        'abstract ',
      )
      ->add($this->getVisibility().' ')
      ->addIf($this->isStatic, 'static ')
      ->addIf($this->isAsync && !$this->isAbstract, 'async ')
      ->add('function ')
      ->getCode();

    return $this->getFunctionDeclarationBase($keywords, $this->isAbstract);
  }

  <<__Override>>
  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    $id = '';
    if ($this->isManualBody()) {
      invariant($this->containingClass, 'The method should belong to a class');
      $id = $this->containingClass->getName().'::';
    }
    $func_declaration = $this->getFunctionDeclaration();

    return $this->appendToBuilderBase(
      $builder,
      $func_declaration,
      $this->isAbstract,
      $id,
    );
  }
}
