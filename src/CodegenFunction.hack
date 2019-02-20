/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

/**
 * Generate code for a function. Please don't use this class directly; instead
 * use the function codegen_function.  E.g.:
 *
 * codegen_function('justDoIt')
 *   ->addParameter('int $x = 3')
 *   ->setReturnType('string')
 *   ->setBody('return (string) $x;')
 *   ->render();
 *
 */
final class CodegenFunction extends CodegenFunctionish {
}
