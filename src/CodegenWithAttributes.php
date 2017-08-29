<?hh // strict
/**
 * Copyright (c) 2017-present, Fred Emmott
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace Facebook\HackCodegen;

trait CodegenWithAttributes {
  private dict<string, vec<string>> $userAttributes = dict[];

  final public function setUserAttribute(
    string $name,
    \Stringish ...$params
  ): this {
    $this->userAttributes[$name] =
      $params |> \HH\Lib\Vec\map($$, $p ==> (string)$p);
    return $this;
  }

  final public function getUserAttributes(): dict<string, vec<string>> {
    return $this->userAttributes;
  }

  final public function getAttributes(): dict<string, vec<string>> {
    return $this->getExtraAttributes()
      |> \HH\Lib\Dict\merge($$, $this->userAttributes);
  }

  final public function hasAttributes(): bool {
    return !\HH\Lib\C\is_empty($this->getAttributes());
  }

  final public function renderAttributes(): ?string {
    $attributes = $this->getAttributes();
    if (\HH\Lib\C\is_empty($attributes)) {
      return null;
    }

    return '<<'.
      \HH\Lib\Str\join(
        ', ',
        \HH\Lib\Dict\map_with_key(
          $attributes,
          ($name, $params) ==> {
            if (\HH\Lib\C\is_empty($params)) {
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
