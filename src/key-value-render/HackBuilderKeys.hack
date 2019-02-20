/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

/** Factory class for creating `IHackBuilderKeyRenderer` instances */
abstract final class HackBuilderKeys {
  /**
   * Render the key with no changes or escaping.
   *
   * This effectively means that the key should be a code literal.
   */
  public static function literal(): IHackBuilderKeyRenderer<string> {
    return new _Private\HackBuilderLiteralRenderer();
  }

  /**
   * Render the key as Hack code that produces the same value.
   *
   * For example, an `int` will be rendered without changes but a `string`
   * will be rendered with quotes.
   */
  public static function export(): IHackBuilderKeyRenderer<arraykey> {
    return new _Private\HackBuilderKeyExportRenderer();
  }

  /** Assume the key is a classname, and render a `::class` constant */
  public static function classname(): IHackBuilderKeyRenderer<string> {
    return new _Private\HackBuilderClassnameRenderer();
  }

  /**
   * The key will be rendered with the specified lambda
   */
  public static function lambda<T as arraykey>(
    (function(IHackCodegenConfig, T): string) $render,
  ): IHackBuilderKeyRenderer<T> {
    return new _Private\HackBuilderKeyLambdaRenderer($render);
  }
}
