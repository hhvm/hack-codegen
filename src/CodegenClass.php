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

  public function setIsFinal(): this {
    $this->isFinal = true;
    return $this;
  }

  public function setIsAbstract(bool $value = true): this {
    $this->isAbstract = $value;
    return $this;
  }

  public function setExtends(string $name): this {
    invariant($this->extendsClass === null, 'extends has already been set');
    $this->extendsClass = $name;
    return $this;
  }

  public function getExtends(): ?string {
    return $this->extendsClass;
  }

  public function getInheritedMethods(): Set<string> {
    $classname_to_methods = $classname ==> {
      try {
        return (new Vector((new \ReflectionClass($classname))->getMethods()))
          ->filter($m ==> !$m->isPrivate())
          ->map($m ==> $m->getName());
      } catch (\ReflectionException $e) {
        // The class doesn't exist (often seen in unit tests).
        // Well, I guess it doesn't have any methods then.
        return Set {};
      }
    };

    $methods = Set {};

    $methods->addAll($classname_to_methods($this->getExtends()));
    foreach ($this->getImplements() as $interface) {
      $methods->addAll($classname_to_methods($interface));
    }
    foreach ($this->getUses() as $trait) {
      $methods->addAll($classname_to_methods($trait));
    }

    $dynamic_yield_methods = $methods->filter(
      $method_name ==> Str::startsWith($method_name, 'gen')
    )->map($gen_method_name ==>
      'get' . Str::substr($gen_method_name, 3, Str::len($gen_method_name)-3)
    );

    return $methods->addAll($dynamic_yield_methods);
  }

  public function setConstructor(CodegenConstructor $constructor): this {
    $this->constructor = $constructor;
    return $this;
  }

  final public function addDeclComment(string $comment): this {
    $this->declComment .= $comment."\n";
    return $this;
  }

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
