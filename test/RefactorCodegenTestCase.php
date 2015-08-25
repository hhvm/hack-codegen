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

final class RefactorCodegenTestCase extends CodegenBaseTest {

  /**
   * In this example, the contents of OldClass get put into NewClass.
   * OldClass had some partially generated code that's going to get
   * transported into the like sections of NewClass.
   */
  public function testClassRename() {
    $old_file_name = Filesystem::createTemporaryFile('codegen', true);
    $old_class = codegen_class('OldClass')
      ->setHasManualDeclarations(true, null, "// Let's see if this shows up")
      ->setHasManualMethodSection(true, null, "// Will this also show up?");

    $codegen_old_file = test_codegen_file($old_file_name)
      ->addClass($old_class);
    $codegen_old_file->save();

    $new_file_name = Filesystem::createTemporaryFile('codegen', true);

    $new_class = codegen_class('NewClass')
      ->setHasManualMethodSection()
      ->setHasManualDeclarations();
    $codegen_new_file = test_codegen_file($new_file_name)
      ->addClass($new_class)
      ->addOriginalFile($old_file_name)
      ->rekeyManualSection('OldClass_header', 'NewClass_header')
      ->rekeyManualSection('OldClass_footer', 'NewClass_footer');

    self::assertUnchanged($codegen_new_file->render());
  }

  /**
   * Same as testClassRename, but we're going to combine the contents
   * of the manual sections into one section instead of two.
   */
  public function testManualSectionMerge() {
    $old_file_name = Filesystem::createTemporaryFile('codegen', true);
    $old_class = codegen_class('OldClass')
      ->setHasManualDeclarations(true, null, "// Let's see if this shows up")
      ->setHasManualMethodSection(true, null, "// Will this also show up?");

    $codegen_old_file = test_codegen_file($old_file_name)
      ->addClass($old_class);
    $codegen_old_file->save();

    $new_file_name = Filesystem::createTemporaryFile('codegen', true);

    $new_class = codegen_class('NewClass')
      ->setHasManualMethodSection(true, "NewClass_manual");
    $codegen_new_file = test_codegen_file($new_file_name)
      ->addClass($new_class)
      ->addOriginalFile($old_file_name)
      ->rekeyManualSection('OldClass_header', 'NewClass_manual')
      ->rekeyManualSection('OldClass_footer', 'NewClass_manual');

    self::assertUnchanged($codegen_new_file->render());
  }
}
