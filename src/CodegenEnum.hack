/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

use namespace HH\Lib\{C, Vec};

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
final class CodegenEnum implements ICodeBuilderRenderer {
  use CodegenWithVisibility;
  use CodegenWithAttributes;
  use HackBuilderRenderer;

  private vec<CodegenEnumMember> $members = vec[];

  private ?string $isAs = null;

  /** Create an instance.
   *
   * You should use `ICodegenFactory::codegenEnum` instead  of directly
   * constructing.
   */
  public function __construct(
    protected IHackCodegenConfig $config,
    private string $name,
    private string $enumType,
  ) {
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

  /** @selfdocumenting */
  public function addMember(CodegenEnumMember $member): this {
    $this->members[] = $member;
    return $this;
  }

  /** @selfdocumenting */
  public function addMembers(
    vec<CodegenEnumMember> $members,
  ): this {
    $this->members = Vec\concat($this->members, $members);
    return $this;
  }

  <<__Override>>
  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    if ($this->docBlock is nonnull) {
      $builder->ensureEmptyLine()->addDocBlock($this->docBlock);
    }
    $builder
      ->ensureEmptyLine()
      ->addLine($this->renderAttributes())
      ->ensureNewLine()
      ->addWithSuggestedLineBreaksf(
      '%s%s%s {',
      'enum '.$this->name,
      HackBuilder::DELIMITER.': '.$this->enumType,
      $this->isAs !== null ? HackBuilder::DELIMITER.'as '.$this->isAs : '',
    );

    if (!C\is_empty($this->members)) {
      $builder->ensureNewLine();
      $builder->indent();
      foreach ($this->members as $m) {
        $m->appendToBuilder($builder);
      }
      $builder->unindent();
    }

    $builder->ensureNewLine();
    $builder->addLine('}');

    return $builder;
  }

	/** @selfdocumenting */
	public function setDocBlock(string $comment): this {
		$this->docBlock = $comment;
		return $this;
	}

  protected bool $hasManualFooter = false;
  protected bool $hasManualHeader = false;
  private ?string $headerName;
  private ?string $headerContents;
  private ?string $footerName;
  private ?string $footerContents;

  public function setHasManualHeader(
    bool $value = true,
    ?string $name = null,
    ?string $contents = null,
  ): this {
    $this->hasManualHeader = $value;
    $this->headerName = $name;
    $this->headerContents = $contents;
    return $this;
  }

	public function setHasManualFooter(
		bool $value = true,
		?string $name = null,
		?string $contents = null,
	): this {
		$this->hasManualFooter = $value;
		$this->footerName = $name;
		$this->footerContents = $contents;
		return $this;
	}

  protected ?string $docBlock;

}
