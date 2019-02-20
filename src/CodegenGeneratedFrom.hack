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
 * Describes how the code was generated in order to write a comment on
 * the generated file.
 * Use one of the helper functions below (codegen_generated_from_*). E.g.
 *
 *   $generated_from =  codegen_generated_from_script();
 *   $file = codegen_file('file.php')
 *     ->setGeneratedFrom($generated_from);
 *
 */
final class CodegenGeneratedFrom implements ICodeBuilderRenderer {
  use HackBuilderRenderer;

  public function __construct(
    protected IHackCodegenConfig $config,
    private string $msg,
  ) {
  }

  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    return $builder->add($this->msg);
  }
}
