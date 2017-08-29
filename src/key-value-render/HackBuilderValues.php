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

abstract final class HackBuilderValues {
  // The value will be used literally, which is useful for example when
  // passing a constant such as MyEnum::Value
  public static function literal(): IHackBuilderValueRenderer<string> {
    return new HackBuilderLiteralRenderer();
  }

  // The value will be exported to be rendered according the type.  E.g. an int
  // will be rendered without changes but a string will be rendered with quotes.
  public static function export(): IHackBuilderValueRenderer<mixed> {
    return new HackBuilderValueExportRenderer();
  }

  public static function valueArray<Tv>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<array<Tv>> {
    return new HackBuilderValueArrayRenderer($vr);
  }

  public static function keyValueArray<Tk as arraykey, Tv>(
    IHackBuilderKeyRenderer<Tk> $kr,
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<array<Tk, Tv>> {
    return new HackBuilderKeyValueArrayRenderer('array', $kr, $vr);
  }

  public static function vector<Tv>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<vec<Tv>> {
    return new HackBuilderValueCollectionRenderer(vec::class, $vr);
  }

  <<__Deprecated("Use vector(), since vecs are immutable")>>
  public static function immVector<Tv>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<vec<Tv>> {
    return new HackBuilderValueCollectionRenderer(vec::class, $vr);
  }

  public static function set<Tv as arraykey>(
    IHackBuilderKeyRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<keyset<Tv>> {
    return new HackBuilderKeyedCollectionRenderer(keyset::class, $vr);
  }

  <<__Deprecated("Use set(), since keysets are immutable")>>
  public static function immSet<Tv as arraykey>(
    IHackBuilderKeyRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<keyset<Tv>> {
    return new HackBuilderKeyedCollectionRenderer(keyset::class, $vr);
  }

  public static function map<Tk as arraykey, Tv>(
    IHackBuilderKeyRenderer<Tk> $kr,
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<dict<Tk, Tv>> {
    return new HackBuilderKeyValueCollectionRenderer(dict::class, $kr, $vr);
  }

  <<__Deprecated("Use map(), since dicts are immutable")>>
  public static function immMap<Tk as arraykey, Tv>(
    IHackBuilderKeyRenderer<Tk> $kr,
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<dict<Tk, Tv>> {
    return new HackBuilderKeyValueCollectionRenderer(dict::class, $kr, $vr);
  }

  public static function shapeWithUniformRendering<Tv>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<shape()> {
    /* HH_IGNORE_ERROR[4110] munging array to shape */
    return new HackBuilderKeyValueArrayRenderer(
      'shape',
      HackBuilderKeys::export(),
      $vr,
    );
  }

  public static function shapeWithPerKeyRendering(
    shape() $value_renderers,
  ): IHackBuilderValueRenderer<shape()> {
    return new HackBuilderShapeRenderer($value_renderers);
  }

  /* The key will be renderered as a classname<T> */
  public static function classname(): IHackBuilderValueRenderer<string> {
    return new HackBuilderClassnameRenderer();
  }

  /**
   * The value will be rendered with the specified lambda
   */
  public static function lambda<T>(
    (function(IHackCodegenConfig, T): string) $render,
  ): IHackBuilderValueRenderer<T> {
    return new HackBuilderValueLambdaRenderer($render);
  }
}
