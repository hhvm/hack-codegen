/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use namespace HH\Lib\Str;

/** Interface for factory methods to create codegen.
 *
 * This is the main entrypoint into the library; most users will want to use
 * the concrete `HackCodegenFactory` class.
 *
 * @see HackCodegenFactory
 * @see CodegenFactoryTrait
 */
interface ICodegenFactory {
  /** @selfdocumenting */
  public function codegenConstructor(): CodegenConstructor;

  /**
   * Create a file with the specified filename/path.
   *
   * @see codegenFilef
   */
  public function codegenFile(string $file): CodegenFile;

  /**
   * Create a file with the specified filename/path using %-placeholder
   * format strings.
   *
   * @see codegenFile
   */
  public function codegenFilef(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenFile;

  /**
   * Create a top-level function (not a method).
   *
   * @see codegenFunctionf
   * @see codegenMethod
   * @see codegenMethodf
   */
  public function codegenFunction(string $name): CodegenFunction;

  /**
   * Create a top-level function (not a method), using a %-placeholder format
   * string for the function name.
   *
   * @see codegenFunction
   * @see codegenMethod
   * @see codegenMethodf
   */
  public function codegenFunctionf(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenFunction;

  /**
   * Create a class.
   *
   * @see codegenClassf
   */
  public function codegenClass(string $name): CodegenClass;

  /** Create a class, using a %-placeholder format string for the class
   * name.
   *
   * @see codegenClass
   */
  public function codegenClassf(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenClass;

  /**
   * Create a class constant.
   *
   * @see codegenConstant
   * @see codegenTypeConstant
   * @see codegenClassConstantf
   */
  public function codegenClassConstant(string $name): CodegenClassConstant;

  /**
   * Create a class constant using a %-placeholder format string for the
    * constant name.
   *
   * @see codegenClassConstant
   */
  public function codegenClassConstantf(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenClassConstant;

  /**
   * Create an enum member.
   */
  public function codegenEnumMember(string $name): CodegenEnumMember;

	/**
	 * Create an enum member using a %-placeholder format string for the constant
   * name.
	 */
  public function codegenEnumMemberf(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenEnumMember;

  /**
   * Create a class type constant.
   */
  public function codegenTypeConstant(string $name): CodegenTypeConstant;

  /**
   * Create a class type constant using a %-placeholder format string for the
   * name.
   */
  public function codegenTypeConstantf(
    Str\SprintfFormatString $name,
    mixed ...$args
  ): CodegenTypeConstant;

  /** Create a top-level constant (not a class constant).
   *
   * @see codegenClassConstant
   * @see codegenConstantf
   */
  public function codegenConstant(string $name): CodegenConstant;

  /**
   * Create a top-level constant (not a class constant), using a %-placeholder
   * format string for the constant name.
   *
   * @see codegenConstant
   */
  public function codegenConstantf(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenConstant;

  /** @selfdocumenting */
  public function codegenEnum(string $name, string $enumType): CodegenEnum;

  /** @selfdocumenting */
  public function codegenInterface(string $name): CodegenInterface;

  /** @selfdocumenting */
  public function codegenTrait(string $name): CodegenTrait;

  /** Create a method.
   *
   * @see codegenMethodf */
  public function codegenMethod(string $name): CodegenMethod;

  /** Create a method, using a %-placeholder format string for the name.
   *
   * @see codegenMethod
   */
  public function codegenMethodf(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenMethod;

  /** Create a `HackBuilder`; this is used to generate code, rather than
   * declarations.
   */
  public function codegenHackBuilder(): HackBuilder;

  /**
   * Generate a declaration that a class implements a specified interface.
   *
   * @see codegenImplementsInterfaces
   */

  public function codegenImplementsInterface(
    string $name,
  ): CodegenImplementsInterface;

  /**
   * Generate declarations that a class implements the specified interfaces.
   *
   * @see codegenImplementsInterface
   */
  public function codegenImplementsInterfaces(
    Traversable<string> $names,
  ): Traversable<CodegenImplementsInterface>;

  /**
   * Generate a class or trait property.
   *
   * @see codegenPropertyf
   */
  public function codegenProperty(string $name): CodegenProperty;

  /**
   * Generate a class or trait property, using a %-placeholder format string
   * for the property name.
   *
   * @see codegenProperty
   */
  public function codegenPropertyf(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenProperty;

  /**
   * Generate a 'use' statement, for adding a trait into a class or another
   * trait.
   *
   * @see codegenUsesTraits
   */
  public function codegenUsesTrait(string $name): CodegenUsesTrait;

  /**
   * Generate a 'use' statements, for adding traits into a class or another
   * trait.
   *
   * @see codegenUsesTrait
   */
  public function codegenUsesTraits(
    Traversable<string> $traits,
  ): Traversable<CodegenUsesTrait>;

  /**
   * Generate a documentation comment indicating that a particular class was
   * used to generate a file.
   *
   * @see codegenGeneratedFromMethod
   * @see codegenGeneratedFromMethodWithkey
   * @see codegenGeneratedFromScript
   */
  public function codegenGeneratedFromClass(
    string $class,
  ): CodegenGeneratedFrom;

  /**
   * Generate a documentation comment indicating that a particular method was
   * used to generate a file.
   *
   * @see codegenGeneratedFromClass
   * @see codegenGeneratedFromMethodWithkey
   * @see codegenGeneratedFromScript
   */
  public function codegenGeneratedFromMethod(
    string $class,
    string $method,
  ): CodegenGeneratedFrom;

  /**
   * Generate a documentation comment indicating that a particular method was
   * used to generate a file, with additional data
   *
   * @param @key Additional data (for example, user input) needed to recreate
   *   the file.
   *
   * @see codegenGeneratedFromClass
   * @see codegenGeneratedFromMethod
   * @see codegenGeneratedFromScript
   */
  public function codegenGeneratedFromMethodWithKey(
    string $class,
    string $method,
    string $key,
  ): CodegenGeneratedFrom;

  /**
   * Generate a documentation comment indicating that a particular script was
   * used to generate a file.
   *
   * @param $script the script used to generate the file. If `null`, it is
   *   inferred from the current process.
   *
   * @see codegenGeneratedFromClass
   * @see codegenGeneratedFromMethod
   * @see codegenGeneratedFromMethodWithkey
   */
  public function codegenGeneratedFromScript(
    ?string $script = null,
  ): CodegenGeneratedFrom;

  /** @selfdocumenting */
  public function codegenShape(CodegenShapeMember ...$members): CodegenShape;

  /**
   * Generate a `type` declaration.
   *
   * This is a transparent type alias.
   *
   * @see codegenNewtype
   */
  public function codegenType(string $name): CodegenType;

  /**
   * Generate a `newtype` declaration.
   *
   * This is an opaque type alias, with the underlying type only visible
   * to the current file.
   *
   * @see codegenType
   */
  public function codegenNewtype(string $name): CodegenType;

  /**
   * Generate a class of trait xhp attribute.
   *
   * @see codegenXHPAttributef
   */
  public function codegenXHPAttribute(string $name): CodegenXHPAttribute;

  /**
   * Generate a class or trait xhp attribute, using a %-placeholder format
   * string for the attribute name.
   *
   * @see codegenXHPAttribute
   */
  public function codegenXHPAttributef(
    Str\SprintfFormatString $format,
    mixed ...$args
  ): CodegenXHPAttribute;
}
