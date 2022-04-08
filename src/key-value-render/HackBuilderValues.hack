/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

/** Factory class for creating `IHackBuilderValueRenderer` instances */
abstract final class HackBuilderValues {
  /**
   * Render the value with no changes or escaping.
   *
   * This effectively means that the value should be a code literal.
   */
  public static function literal(): IHackBuilderValueRenderer<string> {
    return new _Private\HackBuilderLiteralRenderer();
  }

  /**
   * Render the value as Hack code that produces the same value.
   *
   * For example, an `int` will be rendered without changes but a `string`
   * will be rendered with quotes.
   */
  public static function export(): IHackBuilderValueRenderer<mixed> {
    return new _Private\HackBuilderValueExportRenderer();
  }

  /**
   * Render a regex literal, e.g. `re"/foo/"`.
   */
  public static function regex<T as \HH\Lib\Regex\Match>(
  ): IHackBuilderValueRenderer<\HH\Lib\Regex\Pattern<T>> {
    return new _Private\HackBuilderRegexRenderer();
  }

  /**
   * Render a Codegen* value to code, and use it as the value.
   */
  public static function codegen(
  ): IHackBuilderValueRenderer<ICodeBuilderRenderer> {
    return new _Private\HackBuilderCodegenRenderer();
  }

  /** Render a `vec`-like PHP array literal */
  public static function valueArray<Tv>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<vec<Tv>> {
    return new _Private\HackBuilderNativeValueCollectionRenderer(
      ContainerType::PHP_ARRAY,
      $vr,
    );
  }

  /** Render a `dict`-like PHP array literal */
  public static function keyValueArray<Tk as arraykey, Tv>(
    IHackBuilderKeyRenderer<Tk> $kr,
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<dict<Tk, Tv>> {
    return new _Private\HackBuilderNativeKeyValueCollectionRenderer(
      ContainerType::PHP_ARRAY,
      $kr,
      $vr,
    );
  }

  /** Render a `Vector` literal */
  public static function vector<Tv>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<Vector<Tv>> {
    return new _Private\HackBuilderNativeValueCollectionRenderer(
      ContainerType::VECTOR,
      $vr,
    );
  }

  /** Render an `ImmVector` literal */
  public static function immVector<Tv>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<ImmVector<Tv>> {
    return new _Private\HackBuilderNativeValueCollectionRenderer(
      ContainerType::IMM_VECTOR,
      $vr,
    );
  }

  /** Render a `vec` literal */
  public static function vec<Tv>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<vec<Tv>> {
    return new _Private\HackBuilderNativeValueCollectionRenderer(
      ContainerType::VEC,
      $vr,
    );
  }

  /** Render a `Set` literal */
  public static function set<Tv as arraykey>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<Set<Tv>> {
    return new _Private\HackBuilderNativeValueCollectionRenderer(
      ContainerType::SET,
      $vr,
    );
  }

  /** Render an `ImmSet` literal */
  public static function immSet<Tv as arraykey>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<ImmSet<Tv>> {
    return new _Private\HackBuilderNativeValueCollectionRenderer(
      ContainerType::IMM_SET,
      $vr,
    );
  }

  /** Render a `keyset` literal */
  public static function keyset<Tv as arraykey>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<keyset<Tv>> {
    return new _Private\HackBuilderNativeValueCollectionRenderer(
      ContainerType::KEYSET,
      $vr,
    );
  }

  /** Render a `Map` literal */
  public static function map<Tk as arraykey, Tv>(
    IHackBuilderKeyRenderer<Tk> $kr,
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<Map<Tk, Tv>> {
    return new _Private\HackBuilderNativeKeyValueCollectionRenderer(
      ContainerType::MAP,
      $kr,
      $vr,
    );
  }

  /** Render an `ImmMap` literal */
  public static function immMap<Tk as arraykey, Tv>(
    IHackBuilderKeyRenderer<Tk> $kr,
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<ImmMap<Tk, Tv>> {
    return new _Private\HackBuilderNativeKeyValueCollectionRenderer(
      ContainerType::IMM_MAP,
      $kr,
      $vr,
    );
  }

  /** Render a `dict` literal */
  public static function dict<Tk as arraykey, Tv>(
    IHackBuilderKeyRenderer<Tk> $kr,
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<dict<Tk, Tv>> {
    return new _Private\HackBuilderNativeKeyValueCollectionRenderer(
      ContainerType::DICT,
      $kr,
      $vr,
    );
  }

  /** Render a `shape()` literal where all values are rendered the same way */
  public static function shapeWithUniformRendering<Tv>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<shape(/* HH_FIXME[0003] */...)> {
    /* HH_IGNORE_ERROR[4110] munging array to shape */
    /* HH_IGNORE_ERROR[4323] munging array to shape */
    return new _Private\HackBuilderNativeKeyValueCollectionRenderer(
      ContainerType::SHAPE_TYPE,
      HackBuilderKeys::export(),
      $vr,
    );
  }

  /**
   * Render a `shape()` literal with a different renderer for each shape key.
   *
   * @param $value_renderers a shape with the same keys as the literal, but
   *   with appropriate `IHackBuilderValueRenderer`s for the value.
   */
  public static function shapeWithPerKeyRendering(
    shape(/* HH_FIXME[0003] */...) $value_renderers,
  ): IHackBuilderValueRenderer<shape(/* HH_FIXME[0003] */...)> {
    return new _Private\HackBuilderShapeRenderer($value_renderers);
  }

  /** Assume the value is a classname, and render a `::class` constant */
  public static function classname(): IHackBuilderValueRenderer<string> {
    return new _Private\HackBuilderClassnameRenderer();
  }

  /**
   * The value will be rendered with the specified lambda
   */
  public static function lambda<T>(
    (function(IHackCodegenConfig, T): string) $render,
  ): IHackBuilderValueRenderer<T> {
    return new _Private\HackBuilderValueLambdaRenderer($render);
  }
}
