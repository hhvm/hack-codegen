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
      ->codegenShape(
        new CodegenShapeMember('x', 'int'),
        new CodegenShapeMember('y', 'int'),
        new CodegenShapeMember('url', 'string'),
      );

    expect_with_context(static::class, $shape->render())->toBeUnchanged();
  }

  public function testShapeOptionalFields(): void {
    $shape = $this
      ->getCodegenFactory()
      ->codegenShape(
        (new CodegenShapeMember('x', 'int'))->setIsOptional(),
        (new CodegenShapeMember('y', 'int'))->setIsOptional(),
        new CodegenShapeMember('url', 'string'),
      );

    expect_with_context(static::class, $shape->render())->toBeUnchanged();
  }

  public function testImplicitSubtyping(): void {
    $shape = $this
      ->getCodegenFactory()
      ->codegenShape(
        (new CodegenShapeMember('x', 'int'))->setIsOptional(),
        (new CodegenShapeMember('y', 'int'))->setIsOptional(),
        new CodegenShapeMember('url', 'string'),
      )
      ->setAllowsSubtyping(true);

    expect_with_context(static::class, $shape->render())->toBeUnchanged();

  }

  public function testNestedShape(): void {
    $nested = $this
      ->getCodegenFactory()
      ->codegenShape(
        new CodegenShapeMember('x', 'int'),
        new CodegenShapeMember('y', 'int'),
      );

    $shape = $this
      ->getCodegenFactory()
      ->codegenShape(
        new CodegenShapeMember('url', 'string'),
        (new CodegenShapeMember('point', $nested))->setIsOptional(),
      );

    expect_with_context(static::class, $shape->render())->toBeUnchanged();
  }

  public function testMultipleNestedShapes(): void {
    $first = $this
      ->getCodegenFactory()
      ->codegenShape(
        new CodegenShapeMember('x', 'int'),
        new CodegenShapeMember('y', 'int'),
      );

    $second = $this
      ->getCodegenFactory()
      ->codegenShape(
        (new CodegenShapeMember('point', $first))->setIsOptional(),
        new CodegenShapeMember('url', 'string'),
      );

    $shape = $this
      ->getCodegenFactory()
      ->codegenShape(
        new CodegenShapeMember('test', $second),
        (new CodegenShapeMember('key', 'string'))->setIsOptional(),
      );

    expect_with_context(static::class, $shape->render())->toBeUnchanged();
  }
}
