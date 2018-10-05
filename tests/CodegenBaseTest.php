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

abstract class CodegenBaseTest extends \Facebook\HackTest\HackTest {
  protected function getCodegenFactory(): HackCodegenFactory {
    return new HackCodegenFactory(new TestCodegenConfig());
  }
}
