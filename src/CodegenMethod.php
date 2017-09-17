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
 * Generate code for a method. Please don't use this class directly; instead use
 * the function codegen_method.  E.g.:
 *
 * codegen_method('justDoIt')
 *   ->addParameter('int $x = 3')
 *   ->setReturnType('string')
 *   ->setBody('return (string) $x;')
 *   ->setProtected()
 *   ->render();
 *
 */
final class CodegenMethod extends CodegenMethodBase {
  public function setIsOverride(bool $value = true): this {
    $this->isOverride = $value;
    return $this;
  }
}
