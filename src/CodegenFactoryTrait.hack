/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use namespace HH\Lib\{C, Str, Vec};

/**
 * Trait to implement `ICodegenFactory` if no special behavior is provided.
 *
 * You must implement `getConfig()`, but all other methods are final.
 */
trait CodegenFactoryTrait implements ICodegenFactory {
  /** @selfdocumenting */
  public abstract function getConfig(): IHackCodegenConfig;

  final public function codegenConstructor(): CodegenConstructor {
    return new CodegenConstructor($this->getConfig());
  }

  final public function codegenConstant(string $name): CodegenConstant {
    return new CodegenConstant($this->getConfig(), $name);
  }

  final public function codegenConstantf(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenConstant {
    return new CodegenConstant($this->getConfig(), \vsprintf($format, $args));
  }

  final public function codegenClassConstant(
    string $name,
  ): CodegenClassConstant {
    return new CodegenClassConstant($this->getConfig(), $name);
  }

  final public function codegenClassConstantf(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenClassConstant {
    return
      new CodegenClassConstant($this->getConfig(), \vsprintf($format, $args));
  }

  final public function codegenEnumMember(string $name): CodegenEnumMember {
    return new CodegenEnumMember($this->getConfig(), $name);
  }

  final public function codegenEnumMemberf(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenEnumMember {
    return new CodegenEnumMember($this->getConfig(), \vsprintf($format, $args));
  }

  final public function codegenTypeConstant(string $name): CodegenTypeConstant {
    return new CodegenTypeConstant($this->getConfig(), $name);
  }

  final public function codegenTypeConstantf(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenTypeConstant {
    return
      new CodegenTypeConstant($this->getConfig(), \vsprintf($format, $args));
  }

  final public function codegenFile(string $file): CodegenFile {
    return new CodegenFile($this->getConfig(), $file);
  }

  final public function codegenFilef(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenFile {
    return $this->codegenFile(\vsprintf($format, $args));
  }

  final public function codegenFunction(string $name): CodegenFunction {
    return new CodegenFunction($this->getConfig(), $name);
  }

  final public function codegenFunctionf(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenFunction {
    return $this->codegenFunction(\vsprintf($format, $args));
  }

  final public function codegenClass(string $name): CodegenClass {
    return new CodegenClass($this->getConfig(), $name);
  }

  final public function codegenClassf(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenClass {
    return $this->codegenClass(\vsprintf($format, $args));
  }

  final public function codegenEnum(
    string $name,
    string $enumType,
  ): CodegenEnum {
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
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenMethod {
    return $this->codegenMethod(\vsprintf($format, $args));
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
    Traversable<string> $names,
  ): vec<CodegenImplementsInterface> {
    return Vec\map($names, $name ==> $this->codegenImplementsInterface($name));
  }

  final public function codegenProperty(string $name): CodegenProperty {
    return new CodegenProperty($this->getConfig(), $name);
  }

  final public function codegenPropertyf(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenProperty {
    return $this->codegenProperty(\vsprintf($format, $args));
  }

  final public function codegenUsesTrait(string $name): CodegenUsesTrait {
    return new CodegenUsesTrait($this->getConfig(), $name);
  }

  final public function codegenUsesTraits(
    Traversable<string> $names,
  ): vec<CodegenUsesTrait> {
    return Vec\map($names, $x ==> $this->codegenUsesTrait($x));
  }

  final public function codegenGeneratedFromClass(
    string $class,
  ): CodegenGeneratedFrom {
    return
      new CodegenGeneratedFrom($this->getConfig(), 'Generated from '.$class);
  }

  final public function codegenGeneratedFromMethod(
    string $class,
    string $method,
  ): CodegenGeneratedFrom {
    return new CodegenGeneratedFrom(
      $this->getConfig(),
      'Generated from '.$class.'::'.$method.'()',
    );
  }

  final public function codegenGeneratedFromMethodWithKey(
    string $class,
    string $method,
    string $key,
  ): CodegenGeneratedFrom {
    return new CodegenGeneratedFrom(
      $this->getConfig(),
      'Generated from '.$class.'::'.$method."()['".$key."']",
    );
  }

  final public function codegenGeneratedFromScript(
    ?string $script = null,
  ): CodegenGeneratedFrom {
    if ($script === null) {
      $last = \debug_backtrace()
        |> Vec\filter($$, $frame ==> C\contains_key($frame, 'file'))
        |> C\last($$);
      invariant(
        $last !== null,
        "Couldn't get the strack trace.  Please pass the script name to ".
        'codegenGeneratedFromScript',
      );
      $script = $this->codegenFile($last['file'])->getRelativeFileName();
    }
    return new CodegenGeneratedFrom(
      $this->getConfig(),
      'To re-generate this file run '.$script,
    );
  }

  final public function codegenShape(
    CodegenShapeMember ...$members
  ): CodegenShape {
    return new CodegenShape($this->getConfig(), vec($members));
  }

  final public function codegenType(string $name): CodegenType {
    return new CodegenType($this->getConfig(), $name);
  }

  final public function codegenNewtype(string $name): CodegenType {
    return (new CodegenType($this->getConfig(), $name))->newType();
  }

  final public function codegenXHPAttribute(string $name): CodegenXHPAttribute {
    return new CodegenXHPAttribute($this->getConfig(), $name);
  }

  final public function codegenXHPAttributef(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenXHPAttribute {
    return $this->codegenXHPAttribute(\vsprintf($format, $args));
  }
}
