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
 * This class contains the default configuration for Hack code generation.
 *
 * You can call IHackCodegenConfig::setDefaultInstance() to use a custom
 * subclass with the convenience functions (codegen_builder() or and similar),
 * or directly call the constructors passing in your configuration.
 */
final class HackCodegenConfig implements IHackCodegenConfig {
  private static ?IHackCodegenConfig $defaultInstance;

  final public static function getDefaultInstance(): IHackCodegenConfig {
    $instance = self::$defaultInstance;
    if ($instance === null) {
      $instance = new self();
      self::$defaultInstance = $instance;
    }
    return $instance;
  }

  final public static function setDefaultInstance(
    IHackCodegenConfig $instance,
  ): void {
    self::$defaultInstance = $instance;
  }

  public function getFileHeader(): ?Vector<string> {
    // If you want a header on each generated file, insert it here.
    return null;
  }

  public function getSpacesPerIndentation(): int {
    return 2;
  }

  public function getMaxLineLength(): int {
    return 80;
  }

  public function getRootDir(): string {
    return $this->rootDir;
  }

  public function __construct(
    private string $rootDir = __DIR__,
  ) {
  }
}
