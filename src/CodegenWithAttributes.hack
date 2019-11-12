/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */
namespace Facebook\HackCodegen;

use namespace HH\Lib\{C, Dict, Str, Vec};

trait CodegenWithAttributes {
  protected IHackCodegenConfig $config;
  private dict<string, vec<string>> $userAttributes = dict[];

  final public function addEmptyUserAttribute(string $name): this {
    $this->addUserAttribute($name, vec[], HackBuilderValues::export());
    return $this;
  }

  final public function addUserAttribute<T>(
    string $name,
    Traversable<T> $values,
    IHackBuilderValueRenderer<T> $renderer,
  ): this {
    $this->userAttributes[$name] =
      Vec\map($values, $v ==> $renderer->render($this->config, $v));
    return $this;
  }

  final public function getUserAttributes(): dict<string, vec<string>> {
    return $this->userAttributes;
  }

  final public function getAttributes(): dict<string, vec<string>> {
    $attributes = $this->getExtraAttributes();
    return Dict\merge($attributes, $this->userAttributes);
  }

  final public function hasAttributes(): bool {
    return !C\is_empty($this->getAttributes());
  }

  final public function renderAttributes(): ?string {
    $attributes = $this->getAttributes();
    if (C\is_empty($attributes)) {
      return null;
    }

    return '<<'.
      Str\join(
        Dict\map_with_key(
          $attributes,
          ($name, $params) ==> {
            if (C\is_empty($params)) {
              return $name;
            }
            return $name.'('.Str\join($params, ', ').')';
          },
        ),
        ', '
      ).
      '>>';
  }

  protected function getExtraAttributes(): dict<string, vec<string>> {
    return dict[];
  }
}
