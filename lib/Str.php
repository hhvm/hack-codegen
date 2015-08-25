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

final class Str {
  public static function explode(
    string $delimiter,
    string $str,
    ?int $limit=null,
  ): Vector<string> {
    if ($delimiter == '') {
      if ($limit === null) {
        $arr = str_split($str);
      } else {
        $arr = str_split(substr($str, 0, $limit));
      }
    } else {
      if ($limit === null) {
        $arr = explode($delimiter, $str);
      } else {
        $arr = explode($delimiter, $str, $limit);
      }
    }
    return new Vector($arr);
  }

  public static function len(string $str): int {
    return strlen($str);
  }

  public static function replace(
    string $search,
    string $replacement,
    string $haystack,
  ): string {
    return str_replace($search, $replacement, $haystack);
  }

  public static function startsWith(string $str, string $prefix): bool {
    return strncmp($str, $prefix, strlen($prefix)) === 0;
  }

  public static function endsWith(string $str, string $suffix): bool {
    return strlen($str) >= strlen($suffix) &&
      substr_compare($str, $suffix, -strlen($suffix), strlen($suffix)) === 0;
  }

  public static function substr(
    string $str,
    int $offset,
    ?int $length=null,
  ): string {
    $r = is_null($length) ?
      substr($str, $offset) :
      substr($str, $offset, $length);
    if ($r === false) {
      return '';
    }
    return $r;
  }
}
