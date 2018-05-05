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

/**
 * Generate code for a shape. Please don't use this class directly; instead use
 * the function codegenShape_FUTURE.  E.g.:
 *
 * codegenShape_FUTURE(vec([
 *   new CodegenShapeMember('x', 'int'),
 *   new CodegenShapeMember('y', 'int'),
 * ]))
 */
final class CodegenShapeFuture implements ICodeBuilderRenderer {

  use HackBuilderRenderer;

  private ?string $manualAttrsID = null;

  public function __construct(
    protected IHackCodegenConfig $config,
    private vec<CodegenShapeMember> $members = vec([]),
  ) {
  }

  public function setManualAttrsID(?string $id = null): this {
    $this->manualAttrsID = $id;
    return $this;
  }

  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    $builder->addLine('shape(')->indent();

    foreach ($this->members as $member) {
      $prefix = $member->optional ? '?' : '';
      $builder->addLinef(
        "%s'%s' => %s,",
        $prefix,
        $member->name,
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

    return $builder->unindent()->add(')');
  }
}
