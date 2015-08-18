<?hh // strict
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

namespace Facebook\HackCodegen;

/**
 * Generate code for a class. Please don't use this class directly; instead use
 * the function codegen_class.  E.g.:
 *
 * codegen_class('Foo')
 *  ->setExtends('bar')
 *  ->setIsFinal()
 *  ->addMethod(codegen_method('foobar'))
 *  ->render();
 *
 */
final class CodegenClass
  extends CodegenClassBase {

  use CodegenClassWithInterfaces;

  private ?string $extendsClass;
  private string $declComment = '';
  private bool $isFinal = false;
  private bool $isAbstract = false;
  private ?CodegenConstructor $constructor = null;

  public function setIsFinal(bool $value = true): this {
    $this->isFinal = $value;
    return $this;
  }

  public function setIsAbstract(bool $value = true): this {
    $this->isAbstract = $value;
    return $this;
  }

  public function setExtends(string $name): this {
    $this->extendsClass = $name;
    return $this;
  }

  public function getExtends(): ?string {
    return $this->extendsClass;
  }

  public function setConstructor(CodegenConstructor $constructor): this {
    $this->constructor = $constructor;
    return $this;
  }

  /**
   * Add a comment before the class.  Notice that you need to pass the
   * comment characters.  Use this just for HH_FIXME or other ad-hoc uses.
   * For commenting the class, use method setDocBlock.
   * Example (a fake space was included between / and * to avoid a hack error
   * in this comment, but normally you won't have it):
   *
   * $class->addDeclComment('/ * HH_FIXME[4040] * /');
   */
  final public function addDeclComment(string $comment): this {
    $this->declComment .= $comment."\n";
    return $this;
  }

  /**
   * Add a function to wrap calls to the class.  E.g., for MyClass accepting
   * a string parameter it would generate:
   *
   * function MyClass(string $s): MyClass {
   *   return new MyClass($s);
   * }
   */
  public function addConstructorWrapperFunc(
    ?Vector<string> $params = null,
  ): this {
    // Check if parameters are specified explicitly
    $param_full = null;
    if ($params) {
      $param_full = $params;
    } else if ($this->constructor) {
      $param_full = $this->constructor->getParameters();
    }

    if ($param_full) {
      // Extract variable names from parameters
      $param_names = Vector {};
      $re = '/\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/';
      foreach ($param_full as $str) {
        $matches = array();
        if (preg_match_all($re, $str, $matches)) {
          $param_names->addAll($matches[0]);
        }
      }
      $params_str = implode(', ', $param_names);
      $body = 'return new '. $this->getName(). '('. $params_str . ');';

      $this->wrapperFunc =
        codegen_function($this->getName())
          ->setParameters($param_full)
          ->setReturnType($this->getName())
          ->setBody($body);
    } else {
      $this->wrapperFunc =
        codegen_function($this->getName())
          ->setReturnType($this->getName())
          ->setBody('return new '. $this->getName(). '();');
    }
    return $this;
  }

  protected function buildDeclaration(HackBuilder $builder): void {
    $generics_dec = $this->buildGenericsDeclaration();

    $builder->addWithSuggestedLineBreaks(
      '%s%s%s%s%s',
      $this->declComment,
      $this->isAbstract ? 'abstract ' : '',
      $this->isFinal ? 'final ' : '',
      "class ". $this->name.$generics_dec,
      $this->extendsClass !== null ?
        HackBuilder::DELIMITER . "extends " . $this->extendsClass : '',
    );

    $this->renderInterfaceList($builder, 'implements');
  }

  private function buildConstructor(HackBuilder $builder): void {
    $constructor = $this->constructor;
    if ($constructor) {
      $builder
        ->ensureEmptyLine()
        ->addRenderer($constructor);
    }
  }

  protected function appendBodyToBuilder(HackBuilder $builder): void {
    $this->buildTraits($builder);
    $this->buildConsts($builder);
    $this->buildVars($builder);
    $this->buildManualDeclarations($builder);
    $this->buildConstructor($builder);
    $this->buildMethods($builder);
  }
}

/* HH_FIXME[4033] variadic params with type constraints are not supported */
function codegen_class(string $name, ...$args): CodegenClass {
  return new CodegenClass(vsprintf($name, $args));
}

/* HH_FIXME[4033] variadic params with type constraints are not supported */
function codegen_class_name_with_typeargs(string $class, ...$args): string {
  return $class."<\n  ".implode(",\n  ", $args)."\n>";
}
