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
}

/* HH_FIXME[4033] variadic params with type constraints are not supported */
function codegen_method(string $name, ...$args): CodegenMethod {
  return new CodegenMethod(
    HackCodegenConfig::getInstance(),
    vsprintf($name, $args),
  );
}
