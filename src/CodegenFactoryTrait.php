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

trait CodegenFactoryTrait implements ICodegenFactory {
  public abstract function getConfig(): HackCodegenConfig;

  final public function codegenConstructor(): CodegenConstructor {
    return new CodegenConstructor($this->getConfig());
  }

  final public function codegenFile(string $file): CodegenFile {
    return new CodegenFile($this->getConfig(), $file);
  }

  final public function codegenFilef(
    SprintfFormatString $format,
    mixed ...$args
  ): CodegenFile {
    return $this->codegenFile(vsprintf($format, $args));
  }

  final public function codegenFunction(string $name): CodegenFunction {
    return new CodegenFunction($this->getConfig(), $name);
  }

  final public function codegenFunctionf(
    SprintfFormatString $format,
    mixed ...$args
  ): CodegenFunction {
    return $this->codegenFunction(vsprintf($format, $args));
  }

  final public function codegenClass(string $name): CodegenClass {
    return new CodegenClass($this->getConfig(), $name);
  }

  final public function codegenClassf(
    SprintfFormatString $format,
    mixed ...$args
  ): CodegenClass {
    return $this->codegenClass(vsprintf($format, $args));
  }

  final public function codegenEnum(string $name, string $enumType): CodegenEnum {
    return new CodegenEnum($this->getConfig(), $name, $enumType);
  }

  final public function codegenInterface(string $name): CodegenInterface {
    return new CodegenInterface($this->getConfig(), $name);
  }

  final public function codegenTrait(string $name): CodegenTrait {
    return new CodegenTrait($this->getConfig(), $name);
  }

  final public function codegenMethod(string $name): CodegenMethod {
    return new CodegenMethod($this->getConfig(), $name);
  }

  final public function codegenMethodf(
    SprintfFormatString $format,
    mixed ...$args
  ): CodegenMethod {
    return $this->codegenMethod(vsprintf($format, $args));
  }

  final public function codegenHackBuilder(): HackBuilder {
    return new HackBuilder($this->getConfig());
  }

  final public function codegenImplementsInterface(
    string $name,
  ): CodegenImplementsInterface {
    return new CodegenImplementsInterface($this->getConfig(), $name);
  }

  final public function codegenImplementsInterfaces(
    \ConstVector<string> $names,
  ): Vector<CodegenImplementsInterface> {
    return $names
      ->map($name ==> $this->codegenImplementsInterface($name))
      ->toVector();
  }

  final public function codegenMemberVar(string $name): CodegenMemberVar {
    return new CodegenMemberVar($this->getConfig(), $name);
  }

  final public function codegenMemberVarf(
    SprintfFormatString $format,
    mixed ...$args
  ): CodegenMemberVar {
    return $this->codegenMemberVar(vsprintf($format, $args));
  }

  final public function codegenUsesTrait(string $name): CodegenUsesTrait {
    return new CodegenUsesTrait($this->getConfig(), $name);
  }

  final public function codegenUsesTraits(
    \ConstVector<string> $names,
  ): Vector<CodegenUsesTrait> {
    return $names->map($x ==> $this->codegenUsesTrait($x))->toVector();
  }

  final public function codegenGeneratedFromClass(
    string $class,
  ): CodegenGeneratedFrom {
    return
      new CodegenGeneratedFrom($this->getConfig(), "Generated from $class");
  }

  final public function codegenGeneratedFromMethod(
    string $class,
    string $method,
  ): CodegenGeneratedFrom {
    return new CodegenGeneratedFrom(
      $this->getConfig(),
      "Generated from $class::$method()",
    );
  }

  final public function codegenGeneratedFromMethodWithKey(
    string $class,
    string $method,
    string $key,
  ): CodegenGeneratedFrom {
    return new CodegenGeneratedFrom(
      $this->getConfig(),
      "Generated from $class::$method()['$key']",
    );
  }

  final public function codegenGeneratedFromScript(
    ?string $script = null,
  ): CodegenGeneratedFrom {
    if ($script === null) {
      $trace = debug_backtrace();
      $last = end($trace);
      invariant(
        $last !== false,
        "Couldn't get the strack trace.  Please pass the script name to ".
        "codegenGeneratedFromScript",
      );
      $script = $this->codegenFile($last['file'])->getRelativeFileName();
    }
    return new CodegenGeneratedFrom(
      $this->getConfig(),
      "To re-generate this file run $script",
    );
  }

  final public function codegenShape(
    array<string, string> $attrs = array(),
  ): CodegenShape {
    return new CodegenShape($this->getConfig(), $attrs);
  }

  final public function codegenType(string $name): CodegenType {
    return new CodegenType($this->getConfig(), $name);
  }

  final public function codegenNewtype(string $name): CodegenType {
    return (new CodegenType($this->getConfig(), $name))->newType();
  }
}
