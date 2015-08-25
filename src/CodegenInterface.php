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
 * Generate code for an interface. Please don't use this class directly; instead
 * use the function codegen_interface.  E.g.:
 *
 * codegen_interface('IFoo')
 *  ->addMethod(codegen_method('IBar'))
 *
 * Notes:
 * - It can extend one or more other interfaces.
 * - It can have constants and methods.  You don't need to mark your methods as
 *   abstract; that will be done for you.
 * - Interfaces cannot use traits.
 */
final class CodegenInterface extends CodegenClassBase {

  use CodegenClassWithInterfaces;

  protected function buildDeclaration(HackBuilder $builder): void {
    $builder->add('interface '.$this->name);
    $this->renderInterfaceList($builder, 'extends');
  }

  protected function appendBodyToBuilder(HackBuilder $builder): void {
    $this->buildConsts($builder);
    $this->buildMethods($builder);
  }
}

function codegen_interface(string $name): CodegenInterface {
  return new CodegenInterface($name);
}
