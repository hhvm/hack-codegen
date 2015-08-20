<?hh
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

namespace Facebook\HackCodegen;

require_once('CodegenExpectedFile.php');
require_once('CodegenBaseTest.php');
require_once('TestCodegenConfig.php');

/**
 * Runs all the tests found in the test directory (i.e. classes
 * that extend CodegenBaseTest).
 * For each class, it runs all the methods that start with "test".
 * The tests will be checking expected results using the methods
 * found in CodegenBaseTest, such as assertUnchanged.
 * The tester will show a failure for unexpected cases.
 * Tests can also expect an exception as a result, in which case
 * the test needs to define a docblock that contains @expectedException
 * followed by the exception class.
 */
final class CodegenSimpleTester {
  /**
   * Run all the tests.  Return true if they all succeeded.
   */
  public static function run(): bool {
    $files = glob(__DIR__.'/*.php');
    $fail_count = 0;
    foreach ($files as $file) {
      $classes = get_declared_classes();
      require_once($file);
      $test_classes = array_diff(get_declared_classes(), $classes);

      foreach ($test_classes as $class) {
        $ref = new \ReflectionClass($class);
        if ($ref->isAbstract()) {
          continue;
        }
        $instance = $ref->newInstance();
        if (!$instance instanceof CodegenBaseTest) {
          continue;
        }

        echo sprintf("Start testing %s\n", $class);
        $methods = $ref->getMethods();
        foreach ($methods as $method) {
          $name = $method->getName();
          if (!Str::startsWith($name, 'test')) {
            continue;
          }

          $failure = self::runTest($instance, $method);
          if ($failure === null) {
            echo sprintf("  Test %s \e[0;32mpassed\e[0m\n", $name);
          } else {
            $fail_count++;
            echo
              sprintf("  Test %s \e[0;31mfailed\e[0m: %s\n", $name, $failure);
          }
        }
      }
    }
    if ($fail_count === 0) {
      echo "\e[0;32m** All the tests passed! **\e[0m\n";
      return true;
    } else {
      echo sprintf("\e[0;31m** ERROR: %d test failures **\e[0m\n", $fail_count);
      return false;
    }
  }

  /**
   * Run a test method.
   *
   * @return a string containing the failure message, or null if the test
   *         passed
   */
  private static function runTest(
    CodegenBaseTest $instance,
    \ReflectionMethod $method,
  ): ?string {
    $doc_block = $method->getDocComment();
    $expected_exception = null;
    if (is_string($doc_block)) {
      foreach (explode("\n", $doc_block) as $line) {
        $find = strstr($line, '@expectedException');
        if ($find) {
          $expected_exception = trim(substr($find, 19));
        }
      }
    }

    try {
      $method->invoke($instance);
      if ($expected_exception) {
        return "Expected exception $expected_exception, nothing was thrown";
      }
    } catch (\Exception $ex) {
      if ($expected_exception === null) {
        return $ex->getMessage();
      } else {
        $class_name = (new \ReflectionClass($ex))->getShortName();
        if ($class_name !== $expected_exception) {
        return
          "Expected exception $expected_exception, ".get_class($ex).
          " was thrown instead";
	}
      }
    }
    return null;
  }
}
