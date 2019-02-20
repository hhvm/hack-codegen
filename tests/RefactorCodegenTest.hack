/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use type Facebook\HackCodegen\_Private\Filesystem;

final class RefactorCodegenTest extends CodegenBaseTest {

  /**
   * In this example, the contents of OldClass get put into NewClass.
   * OldClass had some partially generated code that's going to get
   * transported into the like sections of NewClass.
   */
  public function testClassRename(): void {
    $cgf = $this->getCodegenFactory();
    $old_file_name = Filesystem::createTemporaryFile('codegen', true);
    $old_class = $cgf
      ->codegenClass('OldClass')
      ->setHasManualDeclarations(true, null, "// Let's see if this shows up")
      ->setHasManualMethodSection(true, null, "// Will this also show up?");

    $codegen_old_file = $cgf->codegenFile($old_file_name)->addClass($old_class);
    $codegen_old_file->save();

    $new_file_name = Filesystem::createTemporaryFile('codegen', true);

    $new_class = $cgf
      ->codegenClass('NewClass')
      ->setHasManualMethodSection()
      ->setHasManualDeclarations();
    $codegen_new_file = $cgf
      ->codegenFile($new_file_name)
      ->addClass($new_class)
      ->addOriginalFile($old_file_name)
      ->rekeyManualSection('OldClass_header', 'NewClass_header')
      ->rekeyManualSection('OldClass_footer', 'NewClass_footer');

    expect_with_context(static::class, $codegen_new_file->render())->toBeUnchanged();
  }

  /**
   * Same as testClassRename, but we're going to combine the contents
   * of the manual sections into one section instead of two.
   */
  public function testManualSectionMerge(): void {
    $cgf = $this->getCodegenFactory();
    $old_file_name = Filesystem::createTemporaryFile('codegen', true);
    $old_class = $cgf
      ->codegenClass('OldClass')
      ->setHasManualDeclarations(true, null, "// Let's see if this shows up")
      ->setHasManualMethodSection(true, null, "// Will this also show up?");

    $codegen_old_file = $cgf->codegenFile($old_file_name)->addClass($old_class);
    $codegen_old_file->save();

    $new_file_name = Filesystem::createTemporaryFile('codegen', true);

    $new_class = $cgf
      ->codegenClass('NewClass')
      ->setHasManualMethodSection(true, "NewClass_manual");
    $codegen_new_file = $cgf
      ->codegenFile($new_file_name)
      ->addClass($new_class)
      ->addOriginalFile($old_file_name)
      ->rekeyManualSection('OldClass_header', 'NewClass_manual')
      ->rekeyManualSection('OldClass_footer', 'NewClass_manual');

    expect_with_context(static::class, $codegen_new_file->render())->toBeUnchanged();
  }
}
