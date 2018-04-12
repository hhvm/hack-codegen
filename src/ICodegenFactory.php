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

interface ICodegenFactory {
  public function codegenConstructor(): CodegenConstructor;

  public function codegenFile(string $file): CodegenFile;

  public function codegenFilef(
    SprintfFormatString $format,
    mixed ...$args
  ): CodegenFile;

  public function codegenFunction(string $name): CodegenFunction;

  public function codegenFunctionf(
    SprintfFormatString $format,
    mixed ...$args
  ): CodegenFunction;

  public function codegenClass(string $name): CodegenClass;

  public function codegenClassf(
    SprintfFormatString $format,
    mixed ...$args
  ): CodegenClass;

  public function codegenConstant(string $name): CodegenConstant;
  public function codegenConstantf(
    SprintfFormatString $format,
    mixed ...$args
  ): CodegenConstant;

  public function codegenEnum(string $name, string $enumType): CodegenEnum;

  public function codegenInterface(string $name): CodegenInterface;

  public function codegenTrait(string $name): CodegenTrait;

  public function codegenMethod(string $name): CodegenMethod;

  public function codegenMethodf(
    SprintfFormatString $format,
    mixed ...$args
  ): CodegenMethod;

  public function codegenHackBuilder(): HackBuilder;

  public function codegenImplementsInterface(
    string $name,
  ): CodegenImplementsInterface;

  public function codegenImplementsInterfaces(
    Traversable<string> $names,
  ): Traversable<CodegenImplementsInterface>;

  public function codegenProperty(string $name): CodegenProperty;

  public function codegenPropertyf(
    SprintfFormatString $format,
    mixed ...$args
  ): CodegenProperty;

  public function codegenUsesTrait(string $name): CodegenUsesTrait;

  public function codegenUsesTraits(
    Traversable<string> $traits,
  ): Traversable<CodegenUsesTrait>;

  public function codegenGeneratedFromClass(
    string $class,
  ): CodegenGeneratedFrom;

  public function codegenGeneratedFromMethod(
    string $class,
    string $method,
  ): CodegenGeneratedFrom;

  public function codegenGeneratedFromMethodWithKey(
    string $class,
    string $method,
    string $key,
  ): CodegenGeneratedFrom;

  public function codegenGeneratedFromScript(
    ?string $script = null,
  ): CodegenGeneratedFrom;

  public function codegenShape(
    KeyedTraversable<string, string> $attrs = array(),
  ): CodegenShape;

  public function codegenType(string $name): CodegenType;

  public function codegenNewtype(string $name): CodegenType;
}
