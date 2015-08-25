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

/**
 * Generate code for a constructor. E.g.
 *
 * codegen_constructor()
 *  ->setBody('$this->x = new Foo();')
 *  ->render();
 */
final class CodegenConstructor extends CodegenMethodBase {
  public function __construct(IHackCodegenConfig $config) {
    parent::__construct($config, '__construct');
  }
}

function codegen_constructor(): CodegenConstructor {
  return new CodegenConstructor(HackCodegenConfig::getInstance());
}
