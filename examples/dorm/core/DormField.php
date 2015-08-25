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

class DormField {
  private bool $optional = false;
  private bool $manual = false;

  public function __construct(
    private string $dbColumn,
    private string $type,
  ) {}

  public function getDbColumn(): string {
    return $this->dbColumn;
  }

  public function getType(): string {
    return $this->type;
  }

  public function optional(): this {
    $this->optional = true;
    return $this;
  }

  public function isOptional(): bool {
    return $this->optional;
  }

  public function manual(): this {
    $this->manual = true;
    return $this;
  }

  public function isManual(): bool {
    return $this->manual;
  }
}

function string_field(string $name): DormField {
  return new DormField($name, 'string');
}

function date_field(string $name): DormField {
  return new DormField($name, 'DateTime');
}

function int_field(string $name): DormField {
  return new DormField($name, 'int');
}

function bool_field(string $name): DormField {
  return new DormField($name, 'bool');
}
