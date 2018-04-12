<?hh // strict
/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen\_Private;

class StrBuffer {

  private string $str = '';
  private bool $detached = false;

  public function append(mixed $value): void {
    $this->str .= (string)$value;
  }

  public function detach(): string {
    invariant(!$this->detached, 'The buffer has been already detached');
    $this->detached = true;
    return $this->str;
  }
}
