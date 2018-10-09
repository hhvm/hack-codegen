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

use namespace HH\Lib\C;

/**
 * Generate code for an enum.
 *
 * ```
 * $factory->codegenEnum('Foo', 'int')
 *  ->setIsAs('int')
 *  ->addConst('NAME', $value, 'Comment...')
 *  ->render();
 * ```
 */
final class CodegenEnum extends CodegenClassish {

  private string $enumType;
  private ?string $isAs = null;

  /** Create an instance.
   *
   * You should use `ICodegenFactory::codegenEnum` instead  of directly
   * constructing.
   */
  public function __construct(
    IHackCodegenConfig $config,
    string $name,
    string $enum_type,
  ) {
    parent::__construct($config, $name);
    $this->enumType = $enum_type;
  }

  /** Make the enum usable directly as the specified type.
   *
   * For example, `->setIsAs('string')` will declare the enum as `as string`,
   * allowing values to be directly passed into functions that take a `string`.
   */
  public function setIsAs(string $is_as): this {
    invariant($this->isAs === null, 'isAs has already been set');
    $this->isAs = $is_as;
    return $this;
  }

  /** @selfdocumenting */
  public function getIsAs(): ?string {
    return $this->isAs;
  }

  <<__Override>>
  protected function buildDeclaration(HackBuilder $builder): void {
    $builder->addWithSuggestedLineBreaksf(
      '%s%s%s',
      "enum ".$this->name,
      HackBuilder::DELIMITER.": ".$this->enumType,
      $this->isAs !== null ? HackBuilder::DELIMITER."as ".$this->isAs : '',
    );
  }

  <<__Override>>
  protected function buildConsts(HackBuilder $builder): void {
    if (C\is_empty($this->consts)) {
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
        ->addWithSuggestedLineBreaksf("%s =\0%s;", $name, (string)$value)
        ->newLine();
    }
  }

  <<__Override>>
  protected function appendBodyToBuilder(HackBuilder $builder): void {
    $this->buildConsts($builder);
    $this->buildManualDeclarations($builder);
  }
}
