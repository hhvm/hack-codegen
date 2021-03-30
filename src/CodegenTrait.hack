/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use namespace HH\Lib\C;

/**
 * Generate code for a trait. Please don't use this class directly; instead use
 * the function codegen_trait.  E.g.:
 *
 * codegen_trait('Foo')
 *  ->addMethod(codegen_method('foobar'))
 *  ->render();
 *
 */
final class CodegenTrait extends CodegenClassish {
  use CodegenClassWithInterfaces;

  private vec<string> $requireClass = vec[];
  private vec<string> $requireInterface = vec[];

  public function addRequireClass(string $class): this {
    $this->requireClass[] = $class;
    return $this;
  }

  public function addRequireInterface(string $interface): this {
    $this->requireInterface[] = $interface;
    return $this;
  }

  <<__Override>>
  protected function buildDeclaration(HackBuilder $builder): void {
    $generics_dec = $this->buildGenericsDeclaration();
    $builder->add('trait '.$this->name.$generics_dec);
    $this->renderInterfaceList($builder, 'implements');
  }

  private function buildRequires(HackBuilder $builder): void {
    if (
      C\is_empty($this->requireClass) && C\is_empty($this->requireInterface)
    ) {
      return;
    }
    $builder->ensureEmptyLine();
    foreach ($this->requireClass as $class) {
      $builder->addLinef('require extends %s;', $class);
    }
    foreach ($this->requireInterface as $interface) {
      $builder->addLinef('require implements %s;', $interface);
    }
  }

  <<__Override>>
  protected function appendBodyToBuilder(HackBuilder $builder): void {
    $this->buildRequires($builder);
    $this->buildTraits($builder);
    $this->buildConsts($builder);
    $this->buildXHPAttributes($builder);
    $this->buildVars($builder);
    $this->buildManualDeclarations($builder);
    $this->buildMethods($builder);
  }
}
