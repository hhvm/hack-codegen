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

final class CodegenShapeTest extends CodegenBaseTest {

  public function testShape(): void {
    $shape = $this
      ->getCodegenFactory()
      ->codegenShape(array('x' => 'int', 'y' => 'int', 'url' => 'string'));

    $this->assertUnchanged($shape->render());
  }
}
