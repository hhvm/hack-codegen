/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

/**
 * Generate code for a constructor.
 *
 * ```
 * $codegen_factory->codegenConstructor()
 *  ->setBody('$this->x = new Foo();')
 *  ->render();
 * ```
 */
final class CodegenConstructor extends CodegenMethodish {
  /** @selfdocumenting */
  public function __construct(IHackCodegenConfig $config) {
    parent::__construct($config, '__construct');
  }
}
