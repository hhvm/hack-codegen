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
  codegen_member_var,
  codegen_method,
  codegen_trait,
  codegen_uses_trait
};

final class CodegenTraitTest extends CodegenBaseTest {

  public function testDocblock(): void {
    $code = codegen_trait('TestDocblockInternal')
      ->setDocBlock(
        "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed ".
        "do eiusmod tempor incididunt ut labore et dolore magna aliqua. ".
        "Ut enim ad minim veniam, quis nostrud exercitation ullamco ".
        "laboris nisi ut aliquip ex ea commodo consequat.\n".
        "Understood?\n".
        "Yes!"
      )
      ->render();

    $this->assertUnchanged($code);
  }

  public function testDemo(): void {
    $code = codegen_trait('DemoInternal')
      ->addRequireClass('RequiredClass')
      ->addRequireInterface('RequiredInterface')
      ->addTrait(codegen_uses_trait('DemoTrait'))
      ->addTrait(
        codegen_uses_trait('WhateverTrait')
        ->setGeneratedFrom(codegen_generated_from_method("Whatever", "Method"))
      )
      ->addTrait(codegen_uses_trait("Useless"))
      ->addConst('MAX_SIZE', 256)
      ->addConst('DEFAULT_NAME', 'MyEnt', 'Default name of Ent.')
      ->addConst('PI', 3.1415)
      ->setHasManualMethodSection()
      ->setHasManualDeclarations()
      ->addVar(
        codegen_member_var('text')->setProtected()->setType('string')
      )
      ->addVar(
        codegen_member_var('id')->setType('?int')->setValue(12345)
      )
      ->addMethod(
        codegen_method('genX')
          ->setProtected()
          ->setDocBlock(
            'This is a 76 characters  comment to test the splitting '.
            'based on indentation.',
          )
          ->setReturnType('Awaitable<int>')
          ->setManualBody()
          ->setBody('// your code here')
      )
      ->setDocBlock('doc-doc-doc!')
      ->render();

    $this->assertUnchanged($code);
  }
}
