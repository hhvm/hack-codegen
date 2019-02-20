/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

/**
 * Generate code for a shape. Please don't use this class directly; instead use
 * the function codegenShape.  E.g.:
 *
 * ```
 * codegenShape(
 *   new CodegenShapeMember('x', 'int'),
 *   (new CodegenShapeMember('y', 'int'))->setIsOptional(),
 * )
 * ```
 *
 */
final class CodegenShape implements ICodeBuilderRenderer {

  use HackBuilderRenderer;

  private ?string $manualAttrsID = null;

  public function __construct(
    protected IHackCodegenConfig $config,
    private vec<CodegenShapeMember> $members,
  ) {
  }

  public function setManualAttrsID(?string $id = null): this {
    $this->manualAttrsID = $id;
    return $this;
  }

  private bool $allowSubtyping = false;
  public function allowsSubtyping(): bool {
    return $this->allowSubtyping;
  }

  public function setAllowsSubtyping(bool $value): this {
    $this->allowSubtyping = $value;
    return $this;
  }

  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    $builder->addLine('shape(')->indent();

    foreach ($this->members as $member) {
      $prefix = $member->getIsOptional() ? '?' : '';
      $builder->addLinef(
        "%s'%s' => %s,",
        $prefix,
        $member->getName(),
        $member->getType(),
      );
    }

    $manual_id = $this->manualAttrsID;
    if ($manual_id !== null) {
      $builder
        ->ensureNewLine()
        ->startManualSection($manual_id)
        ->ensureEmptyLine()
        ->endManualSection();
    }

    if ($this->allowsSubtyping()) {
      $builder->ensureNewLine()->addLine('...');
    }

    return $builder->unindent()->add(')');
  }
}
