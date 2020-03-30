/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen\_Private;

use namespace HH\Lib\Str;

/**
 * Remove the 'HH\' prefix from typehint strings
 * and from strings produced by var_export().
 */
function strip_hh_prefix(
  string $str,
  bool $nonobject_types_only = false,
): string {
  if (!Str\starts_with($str, 'HH\\')) {
    // Bail out early if $str doesn't contain 'HH\'
    return $str;
  }
  $nonobject_types = ImmSet {
    'bool',
    'boolean',
    'int',
    'integer',
    'float',
    'double',
    'real',
    'num',
    'string',
    'resource',
    'mixed',
    'void',
    'this',
    'arraykey',
  };
  $len = \strlen($str);
  $in_literal = '';
  $out = '';
  $c = ' ';
  for ($i = 0; $i < $len; ++$i) {
    $prev = $c;
    $c = $str[$i];
    if ($in_literal !== '') {
      if ($c === '\\') {
        $out .= $c;
        ++$i;
        if ($i >= $len) {
          break;
        }
        $c = $str[$i];
        $out .= $c;
        continue;
      }
      if ($c === $in_literal) {
        $in_literal = '';
      }
    } else {
      if (
        ($c === 'H' || $c === 'h') &&
        \strtoupper(\substr($str, $i, 3)) === 'HH\\' &&
        !\ctype_alnum($prev) &&
        $prev !== '_' &&
        $prev !== '\\'
      ) {
        if ($nonobject_types_only) {
          $sub = \substr($str, $i + 3, 9);
          $sub_len = \strlen($sub);
          $k = 0;
          for (; $k < $sub_len; ++$k) {
            $sub_c = $sub[$k];
            if (!\ctype_alnum($sub_c) && $sub_c !== '_' && $sub_c !== '\\') {
              break;
            }
          }
          $sub = \strtolower(\substr($sub, 0, $k));
          $strip = ($nonobject_types->contains($sub));
        } else {
          $strip = true;
        }
        if ($strip) {
          $i += 2;
          $c = '\\';
          continue;
        }
      }
      if ($c === '\'' || $c === '"') {
        $in_literal = $c;
      }
    }
    $out .= $c;
  }
  return $out;
}
