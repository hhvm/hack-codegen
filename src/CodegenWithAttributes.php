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
  private Map<string, ImmVector<string>> $userAttributes = Map {};

  final public function setUserAttribute(
    string $name,
    /* HH_FIXME[4033] HHVM >= 3.15: string */...$params
  ): this {
    $this->userAttributes[$name] = new ImmVector($params);
    return $this;
  }

  final public function getUserAttributes(
  ): ImmMap<string, ImmVector<string>> {
    return $this->userAttributes->immutable();
  }

  final public function getAttributes(
  ): ImmMap<string, ImmVector<string>> {
    $attributes = $this->getExtraAttributes()->toMap();
    $attributes->setAll($this->userAttributes);
    return $attributes->immutable();
  }

  final public function hasAttributes(): bool {
    return !$this->getAttributes()->isEmpty();
  }

  final public function renderAttributes(): ?string {
    $attributes = $this->getAttributes();
    if ($attributes->isEmpty()) {
      return null;
    }

    return
      '<<'.
      implode(
        ', ',
        $attributes->mapWithKey(
          ($name, $params) ==> {
            if ($params->isEmpty()) {
              return $name;
            }
            return $name.'('.implode(', ', $params).')';
          },
        ),
      ).
      '>>';
  }

  protected function getExtraAttributes(): ImmMap<string, ImmVector<string>> {
    return ImmMap {};
  }
}
