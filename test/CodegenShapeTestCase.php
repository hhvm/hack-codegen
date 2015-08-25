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

final class CodegenShapeTestCase extends CodegenBaseTest {


  public function testShape() {
    $shape = codegen_shape(
      array(
        'x' => 'int',
        'y' => 'int',
        'url' => 'string',
      )
    );

    self::assertUnchanged($shape->render());
  }
}
