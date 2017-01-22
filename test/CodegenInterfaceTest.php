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

use function Facebook\HackCodegen\LegacyHelpers\{
  codegen_generated_from_method,
  codegen_interface,
  codegen_implements_interface,
  codegen_method
};

final class CodegenInterfaceTest extends CodegenBaseTest {

  public function testEmptyInterface(): void {
    $code = codegen_interface('IEmpty')->render();

    $this->assertUnchanged($code);
  }

  public function testExtendsInterfaces(): void {
    $code = codegen_interface('IExtenderOfTwo')
      ->addInterface(codegen_implements_interface('IExtended'))
      ->addInterface(codegen_implements_interface('IOtherExtended'))
      ->render();

    $this->assertUnchanged($code);
  }

  public function testExtendsInterfaceWithGeneratedFrom(): void {
    $code = codegen_interface('IExtenderOfOne')
      ->addInterface(
        codegen_implements_interface('IExtended')
        ->setGeneratedFrom(codegen_generated_from_method('Foo', 'Bar'))
      )
      ->render();

    $this->assertUnchanged($code);
  }

  public function testInterfaceWithStuff(): void {
    $code = codegen_interface('IInterfaceWithStuff')
      ->addMethod(
        codegen_method('genFoo')
        ->setReturnType('Awaitable<mixed>')
        ->setDocBlock("Override this to have the stuff")
      )
      ->addConst('A_CONST', 0)
      ->render();

    $this->assertUnchanged($code);
  }
}
