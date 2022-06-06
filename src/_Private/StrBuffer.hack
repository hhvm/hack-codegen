/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen\_Private;

/** Class for building a string, only permitting append operations.
 *
 * The string can be retrived once via the `detach()` method.
 */
final class StrBuffer {

  private string $str = '';
  private bool $detached = false;

  /** @selfdocumenting */
  public function append(mixed $value): void {
    $this->str .= (string)$value;
  }

  /** Return the build string.
   *
   * This will only work exactly once.
   */
  public function detach(): string {
    invariant(!$this->detached, 'The buffer has been already detached');
    $this->detached = true;
    return $this->str;
  }
}
