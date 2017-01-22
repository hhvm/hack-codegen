<?hh // strict
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

namespace Facebook\HackCodegen\LegacyHelpers;

use Facebook\HackCodegen\{
  CodegenClass,
  CodegenConstructor,
  CodegenEnum,
  CodegenFile,
  CodegenFunction,
  CodegenGeneratedFrom,
  CodegenImplementsInterface,
  CodegenInterface,
  CodegenMemberVar,
  CodegenMethod,
  CodegenShape,
  CodegenTrait,
  CodegenType,
  CodegenUsesTrait,
  HackBuilder,
  HackCodegenConfig
};

function codegen_constructor(): CodegenConstructor {
  return new CodegenConstructor(HackCodegenConfig::getDefaultInstance());
}

/* HH_FIXME[4033] variadic params with type constraints are not supported */
function codegen_file(string $file_name, ...$args): CodegenFile {
  $file_name = vsprintf($file_name, $args);
  return new CodegenFile(HackCodegenConfig::getDefaultInstance(), $file_name);
}

/* HH_FIXME[4033] variadic params with type constraints are not supported */
function codegen_function(string $name, ...$args): CodegenFunction {
  return new CodegenFunction(
    HackCodegenConfig::getDefaultInstance(),
    vsprintf($name, $args),
  );
}

/* HH_FIXME[4033] variadic params with type constraints are not supported */
function codegen_class(string $name, ...$args): CodegenClass {
  return new CodegenClass(
    HackCodegenConfig::getDefaultInstance(),
    vsprintf($name, $args),
  );
}

/* HH_FIXME[4033] variadic params with type constraints are not supported */
function codegen_class_name_with_typeargs(string $class, ...$args): string {
  return $class."<\n  ".implode(",\n  ", $args)."\n>";
}


function codegen_enum(string $name, string $enum_type): CodegenEnum {
  return new CodegenEnum(
    HackCodegenConfig::getDefaultInstance(),
    $name,
    $enum_type,
  );
}


function codegen_interface(string $name): CodegenInterface {
  return new CodegenInterface(HackCodegenConfig::getDefaultInstance(), $name);
}

function codegen_trait(string $name): CodegenTrait {
  return new CodegenTrait(HackCodegenConfig::getDefaultInstance(), $name);
}


/* HH_FIXME[4033] variadic params with type constraints are not supported */
function codegen_method(string $name, ...$args): CodegenMethod {
  return new CodegenMethod(
    HackCodegenConfig::getDefaultInstance(),
    vsprintf($name, $args),
  );
}


function hack_builder(): HackBuilder {
  return new HackBuilder(HackCodegenConfig::getDefaultInstance());
}


function codegen_implements_interface(
  string $name,
): CodegenImplementsInterface {
  return new CodegenImplementsInterface(
    HackCodegenConfig::getDefaultInstance(),
    $name,
  );
}

function codegen_implements_interfaces(
  Vector<string> $names,
): Vector<CodegenImplementsInterface> {
  return $names->map($name ==> codegen_implements_interface($name));
}


/* HH_FIXME[4033] variadic params with type constraints are not supported */
function codegen_member_var(string $name, ...$args): CodegenMemberVar {
  return new CodegenMemberVar(
    HackCodegenConfig::getDefaultInstance(),
    vsprintf($name, $args),
  );
}


function codegen_uses_trait(string $name): CodegenUsesTrait {
  return new CodegenUsesTrait(HackCodegenConfig::getDefaultInstance(), $name);
}

function codegen_uses_traits(
  Vector<string> $names,
): Vector<CodegenUsesTrait> {
  return $names->map($x ==> codegen_uses_trait($x));
}

function codegen_generated_from_class(string $class): CodegenGeneratedFrom {
  return new CodegenGeneratedFrom(
    HackCodegenConfig::getDefaultInstance(),
    "Generated from $class",
  );
}

function codegen_generated_from_method(
  string $class,
  string $method
): CodegenGeneratedFrom {
  return new CodegenGeneratedFrom(
    HackCodegenConfig::getDefaultInstance(),
    "Generated from $class::$method()",
  );
}

function codegen_generated_from_method_with_key(
  string $class,
  string $method,
  string $key
): CodegenGeneratedFrom {
  return new CodegenGeneratedFrom(
    HackCodegenConfig::getDefaultInstance(),
    "Generated from $class::$method()['$key']",
  );
}

function codegen_generated_from_script(
  ?string $script = null
): CodegenGeneratedFrom {
  if ($script === null) {
    $trace = debug_backtrace();
    $last = end($trace);
    invariant(
      $last !== false,
      "Couldn't get the strack trace.  Please pass the script name to ".
      "codegen_generated_from_script",
    );
    $script = codegen_file($last['file'])->getRelativeFileName();
  }
  return new CodegenGeneratedFrom(
    HackCodegenConfig::getDefaultInstance(),
    "To re-generate this file run $script",
  );
}

function codegen_shape(array<string, string> $attrs = array()): CodegenShape {
  return new CodegenShape(HackCodegenConfig::getDefaultInstance(), $attrs);
}

function codegen_type(string $name): CodegenType {
  return new CodegenType(HackCodegenConfig::getDefaultInstance(), $name);
}

function codegen_newtype(string $name): CodegenType {
  return (new CodegenType(HackCodegenConfig::getDefaultInstance(), $name))
    ->newType();
}
