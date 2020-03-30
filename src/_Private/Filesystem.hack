/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackCodegen\_Private;

final class Filesystem {
  public static function createTemporaryFile(
    string $prefix = '',
    bool $cleanup = false,
  ): string {
    $fname = \tempnam(\sys_get_temp_dir(), $prefix);
    if ($cleanup) {
      \register_shutdown_function(() ==> Filesystem::remove($fname));
    }
    return $fname;
  }

  public static function remove(string $path): void {
    if (!\file_exists($path)) {
      return;
    }
    if (!\unlink($path)) {
      throw new \Exception('Unable to remove `'.$path."'.");
    }
  }

  public static function readFile(string $path): string {
    $error_level = \error_reporting(0);
    $data = \file_get_contents($path);
    \error_reporting($error_level);

    if ($data === false) {
      throw new \Exception('Failed to read file `'.$path."'.");
    }

    return $data;
  }

  public static function writeFile(string $path, string $data): void {
    $error_level = \error_reporting(0);
    $res = \file_put_contents($path, $data);
    \error_reporting($error_level);

    if ($res === false) {
      throw new \Exception('Failed to write file `'.$path."'.");
    }
  }

  public static function writeFileIfChanged(string $path, string $data): bool {
    if (\file_exists($path)) {
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
    if (\is_dir($path)) {
      return;
    }
    \mkdir($path, $umask);
  }
}
