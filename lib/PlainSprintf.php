<?hh // strict
/**
 * Copyright (c) 2014, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in root of this source tree. An additional grant of patent
 * rights can be found in the PATENTS file in the same directory.
 *
 */

 namespace Facebook\HackCodegen;

/** Format definition for sprintf-style format strings.
 *
 * Derivived from \PlainSprintf in HHVM itself, but addressing:
 *  - https://github.com/facebook/hhvm/issues/7616
 *  - https://github.com/facebook/hhvm/issues/6909
 */
interface PlainSprintf {
  public function format_d(?int $s) : string;
  public function format_s(?string $s) : string;
  public function format_u(?int $s) : string;
  public function format_b(int $s) : string; // bit strings
  // Technically %f is locale-dependent (and thus wrong), but we don't.
  public function format_f(?float $s) : string;
  public function format_g(?float $s) : string;
  public function format_upcase_f(?float $s) : string;
  public function format_upcase_e(?float $s) : string;
  public function format_x(mixed $s) : string;
  public function format_o(?int $s) : string;
  public function format_c(?int $s) : string;
  public function format_upcase_x(?int $s) : string;
  // %% takes no arguments
  public function format_0x25() : string;
  // Modifiers that don't change the type
  public function format_l() : PlainSprintf;
  public function format_0x20() : PlainSprintf; // ' '
  public function format_0x2b() : PlainSprintf; // '+'
  public function format_0x2d() : PlainSprintf; // '-'
  public function format_0x2e() : PlainSprintf; // '.'
  public function format_0x30() : PlainSprintf; // '0'
  public function format_0x31() : PlainSprintf; // ...
  public function format_0x32() : PlainSprintf;
  public function format_0x33() : PlainSprintf;
  public function format_0x34() : PlainSprintf;
  public function format_0x35() : PlainSprintf;
  public function format_0x36() : PlainSprintf;
  public function format_0x37() : PlainSprintf;
  public function format_0x38() : PlainSprintf;
  public function format_0x39() : PlainSprintf; // '9'
  public function format_0x27() : SprintfQuote;
}
// This should really be a wildcard. It's only used once.
interface SprintfQuote {
  public function format_0x3d() : PlainSprintf;
}
