<?hh
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

namespace Facebook\HackCodegen;

final class CodegenInterfaceTestCase extends CodegenBaseTest {


  public function testEmptyInterface() {
    $code = codegen_interface('IEmpty')->render();

    self::assertUnchanged($code);
  }

  public function testExtendsInterfaces() {
    $code = codegen_interface('IExtenderOfTwo')
      ->addInterface(codegen_implements_interface('IExtended'))
      ->addInterface(codegen_implements_interface('IOtherExtended'))
      ->render();

    self::assertUnchanged($code);
  }

  public function testExtendsInterfaceWithGeneratedFrom() {
    $code = codegen_interface('IExtenderOfOne')
      ->addInterface(
        codegen_implements_interface('IExtended')
        ->setGeneratedFrom(codegen_generated_from_method('Foo', 'Bar'))
      )
      ->render();

    self::assertUnchanged($code);
  }

  public function testInterfaceWithStuff() {
    $code = codegen_interface('IInterfaceWithStuff')
      ->addMethod(
        codegen_method('genFoo')
        ->setReturnType('Awaitable<mixed>')
        ->setDocBlock("Override this to have the stuff")
      )
      ->addConst('A_CONST', 0)
      ->render();

    self::assertUnchanged($code);
  }
}
