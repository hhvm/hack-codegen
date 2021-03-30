/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use namespace HH\Lib\{Regex, Str};

/**
 * Generate code for a class.
 *
 * To construct an instance, use `ICodegenFactory::codegenClass()`.
 *
 * ```
 * $factory->codegenClass('Foo')
 *  ->setExtends('bar')
 *  ->setIsFinal()
 *  ->addMethod(codegen_method('foobar'))
 *  ->render();
 * ```
 */
final class CodegenClass extends CodegenClassish {

  use CodegenClassWithInterfaces;

  private ?string $extendsClass;
  private string $declComment = '';
  private bool $isFinal = false;
  private bool $isAbstract = false;
  private bool $isXHP = false;
  private ?CodegenConstructor $constructor = null;

  /** @selfdocumenting */
  public function setIsFinal(bool $value = true): this {
    $this->isFinal = $value;
    return $this;
  }

  /** @selfdocumenting */
  public function setIsAbstract(bool $value = true): this {
    $this->isAbstract = $value;
    return $this;
  }

  /** @selfdocumenting */
  public function setIsXHP(bool $value = true): this {
    $this->isXHP = $value;
    return $this;
  }

  /** Set the parent class of the generated class. */
  public function setExtends(string $name): this {
    return $this->setExtendsf('%s', $name);
  }

  /**
   * Set the parent class of the generated class, using a %-placeholder format
   * string.
   */
  public function setExtendsf(Str\SprintfFormatString $name, mixed ...$args): this {
    $this->extendsClass = \vsprintf($name, $args);
    return $this;
  }

  /**
   * Get the name of the parent class, or `null` if there is none.
   */
  public function getExtends(): ?string {
    return $this->extendsClass;
  }

  /** @selfdocumenting */
  public function setConstructor(CodegenConstructor $constructor): this {
    $this->constructor = $constructor;
    return $this;
  }

  /**
   * Add a comment before the class.
   *
   * For example:
   *
   * ```
   * $class->addDeclComment('\/* HH_FIXME[4040] *\/');
   * ```
   *
   * @param $comment the full comment, including delimiters
   */
  public function addDeclComment(string $comment): this {
    $this->declComment .= $comment."\n";
    return $this;
  }

  /**
   * Add a factory function to wrap instantiations of to the class.
   *
   * For example, if `MyClass` accepts a single `string` parameter, it would
   * generate:
   *
   * ```
   * function MyClass(string $s): MyClass {
   *   return new MyClass($s);
   * }
   * ```
   *
   * @param $params the parameters to generate, including types. If `null`,
   *   it will be inferred from the constructor if set.
   */
  public function addConstructorWrapperFunc(
    ?Container<string> $params = null,
  ): this {
    // Check if parameters are specified explicitly
    $param_full = null;
    if ($params) {
      $param_full = $params;
    } else if ($this->constructor) {
      $param_full = $this->constructor->getParameters();
    }

    if ($param_full !== null) {
      // Extract variable names from parameters
      $param_names = vec[];
      $re = re"/\\\$[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*/";
      foreach ($param_full as $str) {
        foreach (Regex\every_match($str, $re) as $match) {
          $param_names[] = $match[0];
        }
      }
      $params_str = Str\join( $param_names, ', ');
      $body = 'return new '.$this->getName().'('.$params_str.');';

      $this->wrapperFunc = (
        new CodegenFunction($this->config, $this->getName())
      )
        ->addParameters($param_full)
        ->setReturnType($this->getName())
        ->setBody($body);
    } else {
      $this->wrapperFunc = (
        new CodegenFunction($this->config, $this->getName())
      )
        ->setReturnType($this->getName())
        ->setBody('return new '.$this->getName().'();');
    }
    return $this;
  }

  <<__Override>>
  protected function buildDeclaration(HackBuilder $builder): void {
    $generics_dec = $this->buildGenericsDeclaration();

    $builder->addWithSuggestedLineBreaksf(
      '%s%s%s%s%s%s',
      $this->declComment,
      $this->isAbstract ? 'abstract ' : '',
      $this->isFinal ? 'final ' : '',
      $this->isXHP ? 'xhp ' : '',
      'class '.$this->name.$generics_dec,
      $this->extendsClass !== null
        ? HackBuilder::DELIMITER.'extends '.$this->extendsClass
        : '',
    );

    $this->renderInterfaceList($builder, 'implements');
  }

  private function buildConstructor(HackBuilder $builder): void {
    $constructor = $this->constructor;
    if ($constructor) {
      $builder->ensureEmptyLine()->addRenderer($constructor);
    }
  }

  <<__Override>>
  protected function appendBodyToBuilder(HackBuilder $builder): void {
    $this->buildTraits($builder);
    $this->buildConsts($builder);
    $this->buildXHPAttributes($builder);
    $this->buildVars($builder);
    $this->buildManualDeclarations($builder);
    $this->buildConstructor($builder);
    $this->buildMethods($builder);
  }
}
