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

use namespace HH\Lib\{C, Vec};

final class CLIVerifier {
  private vec<string> $targets;

  public function __construct(
    private vec<string> $argv,
  ) {
    $this->targets = Vec\drop($argv, 1);
  }

  private function verifyFile(string $path): bool {
    $code = \file_get_contents($path);
    if (!SignedSourceBase::isSignedByAnySigner($code)) {
      return true;
    }
    $ok = SignedSourceBase::hasValidSignatureFromAnySigner($code);
    if ($ok) {
      \printf("OK: %s\n", $path);
    } else {
      \fprintf(\STDERR, "MODIFIED: %s\n", $path);
    }
    return $ok;
  }

  private function verifyDirectory(string $path): bool {
    $it = new \RecursiveIteratorIterator(
      new \RecursiveDirectoryIterator($path),
    );
    $good = true;
    foreach ($it as $info) {
      if (!$info->isFile()) {
        continue;
      }
      $good_file = $this->verifyFile($info->getPathname());
      $good = $good && $good_file;
    }
    return $good;
  }

  public function main(): noreturn {
    if (C\is_empty($this->targets)) {
      \fprintf(\STDERR, "Usage: %s path [path ...]\n", $this->argv[0]);
    }
    $files = vec[];
    $dirs = vec[];
    foreach ($this->targets as $target) {
      if (\is_file($target)) {
        $files[] = $target;
      } else if (\is_dir($target)) {
        $dirs[] = $target;
      } else {
        \fprintf(\STDERR, "Don't know how to handle arg '%s'\n", $target);
        exit(1);
      }
    }

    $good = true;
    foreach ($files as $file) {
      $good_file = $this->verifyFile($file);
      $good = $good && $good_file;
    }
    foreach ($dirs as $dir) {
      $good_dir = $this->verifyDirectory($dir);
      $good = $good && $good_dir;
    }
    exit($good ? 0 : 1);
  }
}

<<__EntryPoint>>
function verify_signatures_main(): noreturn {
  $dir = __DIR__;
  while (true) {
    if (\file_exists($dir.'/autoload.hack')) {
      require_once($dir.'/autoload.hack');
      break;
    }
    if (\file_exists($dir.'/vendor/autoload.hack')) {
      require_once($dir.'/vendor/autoload.hack');
      break;
    }

    if ($dir === '') {
      \fwrite(\STDERR, "Failed to find autoloader\n");
      exit(1);
    }

    $pos = \strrpos($dir, '/');
    if ($pos === false) {
      $dir = '';
      continue;
    }

    $dir = \substr($dir, 0, $pos);
  }
  \Facebook\AutoloadMap\initialize();
  (new CLIVerifier(vec(/* HH_FIXME[4110] */ \HH\global_get('argv'))))->main();
  exit(0);
}
