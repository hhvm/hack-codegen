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
 * Generate code for a trait. Please don't use this class directly; instead use
 * the function codegen_trait.  E.g.:
 *
 * codegen_trait('Foo')
 *  ->addMethod(codegen_method('foobar'))
 *  ->render();
 *
 */
final class CodegenTrait extends CodegenClassBase {
  use CodegenClassWithInterfaces;

  private Vector<string> $requireClass = Vector {};
  private Vector<string> $requireInterface = Vector {};

  public function addRequireClass(string $class): this {
    $this->requireClass[] = $class;
    return $this;
  }

  public function addRequireInterface(string $interface): this {
    $this->requireInterface[] = $interface;
    return $this;
  }

  protected function buildDeclaration(HackBuilder $builder): void {
    $generics_dec = $this->buildGenericsDeclaration();
    $builder->add('trait ' . $this->name.$generics_dec);
    $this->renderInterfaceList($builder, 'implements');
  }

  private function buildRequires(HackBuilder $builder): void {
    if ($this->requireClass->isEmpty() && $this->requireInterface->isEmpty()) {
      return;
    }
    $builder->ensureEmptyLine();
    foreach ($this->requireClass as $class) {
      $builder->addLine('require extends %s;', $class);
    }
    foreach ($this->requireInterface as $interface) {
      $builder->addLine('require implements %s;', $interface);
    }
  }

  protected function appendBodyToBuilder(HackBuilder $builder): void {
    $this->buildRequires($builder);
    $this->buildTraits($builder);
    $this->buildConsts($builder);
    $this->buildVars($builder);
    $this->buildManualDeclarations($builder);
    $this->buildMethods($builder);
  }
}

function codegen_trait(string $name): CodegenTrait {
  return new CodegenTrait($name);
}
