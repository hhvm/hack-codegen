<?hh // strict
/**
 * Copyright (c) 2017-present, Fred Emmott
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\HackCodegen;

use namespace HH\Lib\{C, Dict, Vec};

trait CodegenWithAttributes {
  private dict<string, vec<string>> $userAttributes = dict[];

  final public function setUserAttribute(
    string $name,
    \Stringish ...$params
  ): this {
    $this->userAttributes[$name] = Vec\map($params, $p ==> (string)$p);
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
      implode(
        ', ',
        Dict\map_with_key(
          $attributes,
          ($name, $params) ==> {
            if (C\is_empty($params)) {
              return $name;
            }
            return $name.'('.implode(', ', $params).')';
          },
        ),
      ).
      '>>';
  }

  protected function getExtraAttributes(): dict<string, vec<string>> {
    return dict[];
  }
}
