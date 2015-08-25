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
 * Generate code for an enum. Please don't use this class directly; instead use
 * the function codegen_enum.  E.g.:
 *
 * codegen_enum('Foo', 'int')
 *  ->setIsAs('int')
 *  ->addConst('NAME', $value, 'Comment...')
 *  ->render();
 *
 */
final class CodegenEnum extends CodegenClassBase {

  private ?string $declComment = null;
  private string $enumType;
  private ?string $isAs = null;

  public function __construct(string $name, string $enum_type) {
    parent::__construct($name);
    $this->enumType = $enum_type;
  }

  public function setIsAs(string $is_as): this {
    invariant($this->isAs === null, 'isAs has already been set');
    $this->isAs = $is_as;
    return $this;
  }

  public function getIsAs(): ?string {
    return $this->isAs;
  }

  final public function setDeclComment(string $comment): this {
    invariant($this->declComment === null, 'DeclComment has already been set');
    $this->declComment = $comment."\n";
    return $this;
  }

  protected function buildDeclaration(HackBuilder $builder): void {
    $builder->addWithSuggestedLineBreaks(
      '%s%s%s%s',
      (string)$this->declComment,
      "enum ". $this->name,
      HackBuilder::DELIMITER . ": " . $this->enumType,
      $this->isAs !== null ? HackBuilder::DELIMITER . "as " . $this->isAs : '',
    );
  }

  protected function buildConsts(HackBuilder $builder): void {
    if ($this->consts->isEmpty()) {
      return;
    }
    $builder->ensureEmptyLine();

    foreach ($this->consts as $const) {
      list($name, $is_abstract, $value, $comment) = $const;
      invariant(!$is_abstract, 'We do not support abstract consts in Enums.');
      if ($comment !== null) {
        $builder->ensureEmptyLine();
        $builder->addDocBlock($comment);
      }
      $builder
        ->addWithSuggestedLineBreaks(
          "%s =".HackBuilder::DELIMITER."%s;", $name, $value,
        )->newLine();
    }
  }

  protected function appendBodyToBuilder(HackBuilder $builder): void {
    $this->buildConsts($builder);
    $this->buildManualDeclarations($builder);
  }
}

function codegen_enum(string $name, string $enum_type): CodegenEnum {
  return new CodegenEnum($name, $enum_type);
}
