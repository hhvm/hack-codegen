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

final class CodegenShapeFutureTest extends CodegenBaseTest {

  public function testShape(): void {
    $shape = $this
      ->getCodegenFactory()
      ->codegenShape_FUTURE(vec([
        new CodegenShapeMember('x', 'int'),
        new CodegenShapeMember('y', 'int'),
        new CodegenShapeMember('url', 'string'),
      ]));

    $this->assertUnchanged($shape->render());
  }

  public function testShapeOptionalFields(): void {
    $shape = $this
      ->getCodegenFactory()
      ->codegenShape_FUTURE(vec([
        new CodegenShapeMember('x', 'int', true),
        new CodegenShapeMember('y', 'int', true),
        new CodegenShapeMember('url', 'string'),
      ]));

    $this->assertUnchanged($shape->render());
  }

  public function testNestedShape(): void {
    $nested = $this
      ->getCodegenFactory()
      ->codegenShape_FUTURE(vec([
        new CodegenShapeMember('x', 'int'),
        new CodegenShapeMember('y', 'int'),
      ]));

    $shape = $this
      ->getCodegenFactory()
      ->codegenShape_FUTURE(vec([
        new CodegenShapeMember('url', 'string'),
        new CodegenShapeMember('point', $nested, true),
      ]));

    $this->assertUnchanged($shape->render());
  }

  public function testNestedShapeLegacy(): void {
    $nested = $this
      ->getCodegenFactory()
      ->codegenShape(array('x' => 'int', 'y' => 'int'));

    $shape = $this
      ->getCodegenFactory()
      ->codegenShape_FUTURE(vec([
        new CodegenShapeMember('url', 'string'),
        new CodegenShapeMember('point', $nested, true),
      ]));

    $this->assertUnchanged($shape->render());
  }

  public function testMultipleNestedShapes(): void {
    $first = $this
      ->getCodegenFactory()
      ->codegenShape_FUTURE(vec([
        new CodegenShapeMember('x', 'int'),
        new CodegenShapeMember('y', 'int'),
      ]));

    $second = $this
      ->getCodegenFactory()
      ->codegenShape_FUTURE(vec([
        new CodegenShapeMember('point', $first, true),
        new CodegenShapeMember('url', 'string'),
      ]));

    $shape = $this
      ->getCodegenFactory()
      ->codegenShape_FUTURE(vec([
        new CodegenShapeMember('test', $second),
        new CodegenShapeMember('key', 'string', true),
      ]));

    $this->assertUnchanged($shape->render());
  }
}
