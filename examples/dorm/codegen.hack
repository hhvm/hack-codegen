#!/usr/bin/env hhvm
/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen;

<<__EntryPoint>>
async function dorm_codegen_cli_main_async(): Awaitable<noreturn> {
  require_once(__DIR__.'/../../vendor/autoload.hack');
  \Facebook\AutoloadMap\initialize();
  $exit_code = await DormCodegenCLI::runAsync();
  exit($exit_code);
}
