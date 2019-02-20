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
 * An `IHackCodegenFactory` that takes a configuration object.
 *
 * To avoid needing to specify the configuration at every call site, you
 * can create your own class using `CodegenFactoryTrait`, or directly
 * implement `ICodegenFactory`.
 */
final class HackCodegenFactory {
  use CodegenFactoryTrait;

  /** @selfdocumenting */
  public function __construct(private IHackCodegenConfig $config) {
  }

  <<__Override>>
  public function getConfig(): IHackCodegenConfig {
    return $this->config;
  }
}
