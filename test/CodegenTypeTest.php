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

final class CodegenTypeTest extends CodegenBaseTest {

  public function testType(): void {
    $cgf = $this->getCodegenFactory();
    $type = $cgf->codegenType('Point')->setType('(int, int)');
    $this->assertUnchanged($type->render());
  }

  public function testNewType(): void {
    $cgf = $this->getCodegenFactory();
    $type = $cgf->codegenNewtype('Point')->setType('(int, int)');
    $this->assertUnchanged($type->render());
  }

  public function testShape(): void {
    $cgf = $this->getCodegenFactory();
    $type = $cgf
      ->codegenType('Point')
      ->setShape($cgf->codegenShape(array('x' => 'int', 'y' => 'int')));

    $this->assertUnchanged($type->render());
  }

  public function testShape_FUTURE(): void {
    $cgf = $this->getCodegenFactory();
    $type = $cgf
      ->codegenType('Point')
      ->setShape($cgf->codegenShape_FUTURE(
        new CodegenShapeMember('x', 'int'),
        new CodegenShapeMember('y', 'int'),
      ));

    $this->assertUnchanged($type->render());
  }
}
