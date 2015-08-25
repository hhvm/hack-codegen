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
 * Generate code for a shape. Please don't use this class directly; instead use
 * the function codegen_shape.  E.g.:
 *
 * codegen_shape(array('x' => 'int', 'y' => 'int'))
 */
final class CodegenShape
  implements ICodeBuilderRenderer {

  use HackBuilderRenderer;

  private ?string $manualAttrsID = null;

  public function __construct(
    private array<string, string> $attrs = array(),
  ) {}

  public function setManualAttrsID(?string $id = null): this {
    $this->manualAttrsID = $id;
    return $this;
  }

  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    $builder
      ->addLine('shape(')
      ->indent();

    foreach ($this->attrs as $name => $type) {
      $builder->addLine("'%s' => %s,", $name, $type);
    }

    $manual_id = $this->manualAttrsID;
    if ($manual_id !== null) {
      $builder
        ->ensureNewLine()
        ->beginManualSection($manual_id)
        ->ensureEmptyLine()
        ->endManualSection();
    }

    return $builder
      ->unindent()
      ->add(')');
  }
}

function codegen_shape(array<string, string> $attrs = array()): CodegenShape {
  return new CodegenShape($attrs);
}
