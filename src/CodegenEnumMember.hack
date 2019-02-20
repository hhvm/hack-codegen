/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

final class CodegenEnumMember
  extends CodegenConstantish
  implements ICodeBuilderRenderer {
  use HackBuilderRenderer;

  public function __construct(
    protected IHackCodegenConfig $config,
    string $name,
  ) {
    parent::__construct($config, $name);
  }

  <<__Override>>
  public function appendToBuilder(HackBuilder $builder): HackBuilder {
    $value = $this->getValue();
    invariant($value !== null, 'enum members must have a value');
    $db = $this->getDocBlock();
    if ($db is nonnull) {
      $builder->ensureEmptyLine()->addDocBlock($db);
    }
    return $builder
      ->ensureNewLine()
      ->addWithSuggestedLineBreaksf(
        '%s%s= %s',
        $this->getName(),
        HackBuilder::DELIMITER,
        $value,
      )
      ->addLine(';');
  }
}
