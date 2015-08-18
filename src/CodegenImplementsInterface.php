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

/**
 * Describes an implemented interface, optionally including a comment, like:
 *
 * // Generated from CowSchema::Moo()
 * IDoesMoo
 *
 * Use the methods codegen_implements_interface[s] to instantiate it. E.g.:
 *
 * $i = codegen_implements_interface('IUser')
 *   ->setComment('Some kind of user');
 * $class = codegen_class('MyClass')
 *   ->addInterface($i);
 */
final class CodegenImplementsInterface
  implements ICodeBuilderRenderer {

  use HackBuilderRenderer;

  private ?string $comment;

  public function __construct(private string $name) {}

  public function getName(): string {
    return $this->name;
  }

  public function setComment(string $format, ...): this {
    $comment = vsprintf($format, array_slice(func_get_args(), 1));
    $this->comment = $comment;
    return $this;
  }

  public function setGeneratedFrom(CodegenGeneratedFrom $from): this {
    $this->setComment($from->render());
    return $this;
  }

  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    return $builder
      ->indent()
      ->addInlineComment($this->comment)
      ->add($this->name)
      ->unindent();
  }
}

function codegen_implements_interface(
  string $name,
): CodegenImplementsInterface {
  return new CodegenImplementsInterface($name);
}

function codegen_implements_interfaces(
  Vector<string> $names,
): Vector<CodegenImplementsInterface> {
  $interfaces = Vector {};
  foreach ($names as $name) {
    $interfaces->add(new CodegenImplementsInterface($name));
  }
  return $interfaces;
}
