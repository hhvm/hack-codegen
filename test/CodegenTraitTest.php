<?hh // strict
/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

final class CodegenTraitTest extends CodegenBaseTest {

  public function testDocblock(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenTrait('TestDocblockInternal')
      ->setDocBlock(
        "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed ".
        "do eiusmod tempor incididunt ut labore et dolore magna aliqua. ".
        "Ut enim ad minim veniam, quis nostrud exercitation ullamco ".
        "laboris nisi ut aliquip ex ea commodo consequat.\n".
        "Understood?\n".
        "Yes!",
      )
      ->render();

    $this->assertUnchanged($code);
  }

  public function testDemo(): void {
    $cgf = $this->getCodegenFactory();
    $code = $cgf
      ->codegenTrait('DemoInternal')
      ->addRequireClass('RequiredClass')
      ->addRequireInterface('RequiredInterface')
      ->addTrait($cgf->codegenUsesTrait('DemoTrait'))
      ->addTrait(
        $cgf
          ->codegenUsesTrait('WhateverTrait')
          ->setGeneratedFrom(
            $cgf->codegenGeneratedFromMethod("Whatever", "Method"),
          ),
      )
      ->addTrait($cgf->codegenUsesTrait("Useless"))
      ->addConst('MAX_SIZE', 256)
      ->addConst('DEFAULT_NAME', 'MyEnt', 'Default name of Ent.')
      ->addConst('PI', 3.1415)
      ->setHasManualMethodSection()
      ->setHasManualDeclarations()
      ->addProperty(
        $cgf->codegenProperty('text')->setProtected()->setType('string'),
      )
      ->addProperty(
        $cgf
          ->codegenProperty('id')
          ->setType('?int')
          ->setValue(12345, HackBuilderValues::export()),
      )
      ->addMethod(
        $cgf
          ->codegenMethod('genX')
          ->setProtected()
          ->setDocBlock(
            'This is a 76 characters  comment to test the splitting '.
            'based on indentation.',
          )
          ->setReturnType('Awaitable<int>')
          ->setManualBody()
          ->setBody('// your code here'),
      )
      ->setDocBlock('doc-doc-doc!')
      ->render();

    $this->assertUnchanged($code);
  }
}
