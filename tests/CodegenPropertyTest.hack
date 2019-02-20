/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

final class CodegenPropertyTest extends CodegenBaseTest {

  public function testSimple(): void {
    $code = $this->getCodegenFactory()->codegenProperty('foo')->render();
    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testPublicStatic(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenProperty('foo')
      ->setPublic()
      ->setIsStatic()
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testTyped(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenProperty('name')
      ->setProtected()
      ->setType('string')
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testTypedWithFalsyValue(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenProperty('count')
      ->setType('?int')
      ->setValue(0, HackBuilderValues::export())
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testArrayValues(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenProperty('languages')
      ->setIsStatic()
      ->setValue(
        array('en' => 'English', 'es' => 'Spanish', 'fr' => 'French'),
        HackBuilderValues::export(),
      )
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testVector(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenProperty('parameters')
      ->setType('Vector<string>')
      ->setValue('Vector {}', HackBuilderValues::literal())
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

  public function testDocBlock(): void {
    $code = $this
      ->getCodegenFactory()
      ->codegenProperty('thingWithComment')
      ->setInlineComment('a comment')
      ->render();

    expect_with_context(static::class, $code)->toBeUnchanged();
  }

}
