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

interface ICodegenFactory {
  public function codegenConstructor(): CodegenConstructor;

  public function codegenFile(string $file): CodegenFile;

  public function codegenFilef(
    SprintfFormatString $format,
    /* HH_FIXME[4033] mixed */ ...$args
  ): CodegenFile;

  public function codegenFunction(string $name): CodegenFunction;

  public function codegenFunctionf(
    SprintfFormatString $format,
    /* HH_FIXME[4033] mixed */ ...$args
  ): CodegenFunction;

  public function codegenClass(string $name): CodegenClass;

  public function codegenClassf(
    SprintfFormatString $format,
    /* HH_FIXME[4033] mixed */ ...$args
  ): CodegenClass;

  public function codegenEnum(string $name, string $enumType): CodegenEnum;

  public function codegenInterface(string $name): CodegenInterface;

  public function codegenTrait(string $name): CodegenTrait;

  public function codegenMethod(string $name): CodegenMethod;

  public function codegenMethodf(
    SprintfFormatString $format,
    /* HH_FIXME[4033] mixed */ ...$args
  ): CodegenMethod;

  public function codegenHackBuilder(): HackBuilder;

  public function codegenImplementsInterface(string $name): CodegenImplementsInterface;

  public function codegenImplementsInterfaces(
    \ConstVector<string> $names,
  ): Vector<CodegenImplementsInterface>;

  public function codegenMemberVar(string $name): CodegenMemberVar;

  public function codegenMemberVarf(
    SprintfFormatString $format,
    /* HH_FIXME[4033] mixed */ ...$args
  ): CodegenMemberVar;

  public function codegenUsesTrait(string $name): CodegenUsesTrait;

  public function codegenUsesTraits(
    \ConstVector<string> $traits,
  ): Vector<CodegenUsesTrait>;

  public function codegenGeneratedFromClass(string $class): CodegenGeneratedFrom;

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
    array<string, string> $attrs = array(),
  ): CodegenShape;

  public function codegenType(string $name): CodegenType;

  public function codegenNewtype(string $name): CodegenType;
}
