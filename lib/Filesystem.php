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

final class Filesystem {
  public static function createTemporaryFile(
    string $prefix = '',
    bool $cleanup = false
  ): string {
    $fname = tempnam(sys_get_temp_dir(), $prefix);
    if ($cleanup) {
      register_shutdown_function(array('Filesystem', 'remove'), $fname);
    }
    return $fname;
  }

  public static function remove(string $path): void {
    if (!file_exists($path)) {
      return;
    }
    if (!unlink($path)) {
      throw new \Exception("Unable to remove `{$path}'.");
    }
  }

  public static function readFile(string $path): string {
    $data = @file_get_contents($path);
    if ($data === false) {
      throw new \Exception("Failed to read file `{$path}'.");
    }

    return $data;
  }

  public static function writeFile(string $path, string $data): void {
    $res = @file_put_contents($path, $data);

    if ($res === false) {
      throw new \Exception("Failed to write file `{$path}'.");
    }
  }

  public static function writeFileIfChanged(string $path, string $data): bool {
    if (file_exists($path)) {
      $current = self::readFile($path);
      if ($current === $data) {
        return false;
      }
    }
    self::writeFile($path, $data);
    return true;
  }

  public static function createDirectory(
    string $path,
    int $umask = 0777,
  ): void {
    if (is_dir($path)) {
      return;
    }
    mkdir($path, $umask);
  }
}
