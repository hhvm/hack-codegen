/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

final class CodegenTypeTest extends CodegenBaseTest {

  public function testType(): void {
    $cgf = $this->getCodegenFactory();
    $type = $cgf->codegenType('Point')->setType('(int, int)');
    expect_with_context(static::class, $type->render())->toBeUnchanged();
  }

  public function testNewType(): void {
    $cgf = $this->getCodegenFactory();
    $type = $cgf->codegenNewtype('Point')->setType('(int, int)');
    expect_with_context(static::class, $type->render())->toBeUnchanged();
  }

  public function testShape(): void {
    $cgf = $this->getCodegenFactory();
    $type = $cgf
      ->codegenType('Point')
      ->setShape($cgf->codegenShape(
        new CodegenShapeMember('x', 'int'),
        new CodegenShapeMember('y', 'int'),
      ));

    expect_with_context(static::class, $type->render())->toBeUnchanged();
  }
}
