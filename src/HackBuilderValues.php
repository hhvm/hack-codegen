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
    return new HackBuilderLiteralRender();
  }

  // The value will be exported to be rendered according the type.  E.g. an int
  // will be rendered without changes but a string will be rendered with quotes.
  public static function export(): IHackBuilderValueRenderer<mixed> {
    return new HackBuilderValueExportRender();
  }

  public static function valueArray<Tv>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<array<Tv>> {
    return new HackBuilderValueArrayRenderer($vr);
  }

  public static function keyValueArray<Tk as arraykey, Tv>(
    IHackBuilderKeyRenderer<Tk> $kr,
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<array<Tk,Tv>> {
    return new HackBuilderKeyValueArrayRenderer($kr, $vr);
  }

  public static function vector<Tv>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<Vector<Tv>> {
    return new HackBuilderValueCollectionRenderer(Vector::class, $vr);
  }

  public static function immVector<Tv>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<ImmVector<Tv>> {
    return new HackBuilderValueCollectionRenderer(ImmVector::class, $vr);
  }

  public static function set<Tv>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<Set<Tv>> {
    return new HackBuilderValueCollectionRenderer(Set::class, $vr);
  }

  public static function immSet<Tv>(
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<ImmSet<Tv>> {
    return new HackBuilderValueCollectionRenderer(ImmSet::class, $vr);
  }

  public static function map<Tk as arraykey, Tv>(
    IHackBuilderKeyRenderer<Tk> $kr,
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<Map<Tk,Tv>> {
    return new HackBuilderKeyValueCollectionRenderer(Map::class, $kr, $vr);
  }

  public static function immMap<Tk as arraykey, Tv>(
    IHackBuilderKeyRenderer<Tk> $kr,
    IHackBuilderValueRenderer<Tv> $vr,
  ): IHackBuilderValueRenderer<ImmMap<Tk,Tv>> {
    return new HackBuilderKeyValueCollectionRenderer(ImmMap::class, $kr, $vr);
  }
}
