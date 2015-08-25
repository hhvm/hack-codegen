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
 * The main purposes of this class are to:
 * 1) Serialize a Map<string,string> into a file.
 * Those files have the extension .codegen, they are basically used
 * for testing, similarly to sql3 files used by shim.
 * 2) Prompt the user to accept/refuse any change between an old
 * Map<string,string> and a new one.
 *
 * We call the keys of our Map<string,string> a token. It's simply
 * a unique identifier (usually the name of the test function) for the value
 * (usually the expected output of that test function).
 *
 * The main (only) user is CodegenAssertUnchanged that you can use in your
 * tests (see docblock).
 */
final class CodegenExpectedFile {
  const string EXTENSION = '.codegen';
  const string SEPARATOR = '!@#$%codegentest:';

  /**
   * Given a class name, return the uri where the codegen file should
   * be written (the uri where the class is defined -php+codegen).
   */
  public static function getPath(string $class_name): string {
    $ref = new \ReflectionClass($class_name);
    $source_file = $ref->getFileName();
    // Get classname without namespace
    $class_name = $ref->getShortName(); 
    return dirname($source_file).'/'.$class_name.self::EXTENSION;
  }

  /**
   * Users of CodegenExpectedFile can use whatever token they want,
   * but a common case if to use the name of the test function.
   * This returns the name of the first function that starts with 'test'
   * in the current stack trace.
   */
  public static function findToken(): string {
    $token = null;
    // Get caller function name
    $stack = debug_backtrace();
    foreach ($stack as $function) {
      $function_name = $function['function'];
      if (Str::startsWith($function_name, 'test')) {
        $token = $function_name;
        break;
      }
    }
    invariant(
      $token !== null,
      'Test framework was unable to find a function starting with '.
      '"test" when looking through the stack.',
    );
    return $token;
  }

  //
  // Read/Write codegen file
  //

  /**
   * Parse a existing codegen file, returns the Map<string,string>.
   */
  public static function parseFile(string $file_name): Map<string, string> {
    $map = Map {};

    if (!file_exists($file_name)) {
      return $map;
    }

    $lines = file($file_name);
    invariant(
      $lines !== false,
      'Fail to open the file %s for reading',
      $file_name,
    );

    $generated = array_shift($lines);
    invariant(
      rtrim($generated) === '@'.'generated',
      'Codegen test record file should start with a generated tag',
    );

    $token = null;
    $expected = '';
    foreach ($lines as $line) {
      if (Str::startsWith($line, self::SEPARATOR)) {
        if ($token !== null) {
          // We always add 1 newline at the end
          $expected = substr($expected, 0, -1);
          $map->set($token, $expected);
        }
        // Format is separator:token\n
        $token = substr(rtrim($line), strlen(self::SEPARATOR));
        $expected = '';
        continue;
      }

      $expected .= self::unescapeTokens($line);
    }
    if ($token !== null) {
      // We always add 1 newline at the end
      $expected = substr($expected, 0, -1);
      $map->set($token, $expected);
    }
    return $map;
  }

  /**
   * Create or override a codegen file with a merge of an old
   * Map<string,string> and a new one.
   * User is prompted for all mismatched values.
   */
  final public static function writeExpectedFile(
    string $file_name,
    Map<string, string> $new_expected,
    Map<string, string> $old_expected,
  ): void {
    $final_expected = Map {};

    foreach ($new_expected as $token => $new_value) {
      if ($old_expected->contains($token)) {
        $old_value = $old_expected->at($token);
        if ($new_value === $old_value) {
          // No change, keep it
          $final_expected->set($token, $new_value);
        } else {
          // Prompt user to accept the change
          $update_it = self::promptForUpdate(
            $token,
            $old_value,
            $new_value,
          );
          if ($update_it === true) {
            $final_expected->set($token, $new_value);
          } else {
            $final_expected->set($token, $old_value);
          }
        }
      } else {
        // Prompt user to accept the new value
        $add_it = self::promptForAdd($token, $new_value);
        if ($add_it === true) {
          $final_expected->set($token, $new_value);
        }
      }
    }

    foreach ($old_expected as $token => $old_value) {
      if ($new_expected->contains($token)) {
        // We already made the decision
        continue;
      }

      // Prompt user to remove the old value
      $remove_it = self::promptForRemove($token, $old_value);
      if ($remove_it === false) {
        $final_expected->set($token, $old_value);
      }
    }

    self::writeFile($file_name, $final_expected);
  }

  /**
   * Create or override a codegen file with a new Map<string,string>.
   */
  public static function writeFile(
    string $file_name,
    Map<string, string> $map,
  ): void {
    if (!file_exists($file_name)) {
      touch($file_name);
      chmod($file_name, 0666);
    }
    $file = fopen($file_name, 'w');
    invariant(
      $file !== false,
      'Fail to open the file %s for writing',
      $file_name,
    );

    // Sorting is important or we would have merge conflict
    // in the generated file all the time
    $tokens = $map->keys();
    sort($tokens);

    fwrite($file, '@'."generated\n");
    foreach ($tokens as $token) {
      $value = self::escapeTokens($map->at($token));

      // Format is separator:token\n
      fwrite($file, self::SEPARATOR.$token."\n");
      // We always add 1 newline at the end
      fwrite($file, $value."\n");
    }
    fclose($file);
  }

  //
  // Stdout/Stdin helpers
  //

  /**
   * Helper to display any message on stdout
   */
  final public static function display(string $format, ...): void {
    $args = array_slice(func_get_args(), 1);
    $message = vsprintf($format, $args);
    echo 'gentest> '.$message ."\n";
  }

  private static function promptForAdd(string $token, string $value): bool {
    self::display('A new test %s was added, expected value is:', $token);
    echo $value."\n";
    return self::prompt('Do you accept the new expected value ?');
  }

  private static function promptForUpdate(
    string $token,
    string $old_value,
    string $new_value,
  ): bool {
    self::display('Change of expected value for test %s:', $token);
    echo difference_render_fast($old_value, $new_value);
    return self::prompt('Do you accept to update the expected value ?');
  }

  private static function promptForRemove(string $token, string $value): bool {
    self::display('An old test %s was removed, expected value was:', $token);
    echo $value."\n";
    return self::prompt('Do you accept to remove the expected value ?');
  }

  /**
   * Helper to ask the user for validation on stdin
   */
  private static function prompt(string $message): bool {
    while (true) {
      self::display('%s (Y/n)', $message);
      echo '> ';
      $result = strtolower(trim(fgets(STDIN)));
      if ($result === '' || $result === 'y') {
        return true;
      }
      if ($result === 'n') {
        return false;
      }
    }
    invariant_violation('For Hack');
  }

  //
  // Signature helpers
  //

  /**
   * Escape the tokens that carry signatures, so that when writing those to
   * the .codegen file, it doesn't seem like that's the file signature.
   */
  final private static function escapeTokens(string $s): string {
    $result = Str::replace('@'.'generated', '@-generated', $s);
    return Str::replace(
      '@'.'partially-generated',
      '@-partially-generated',
      $result,
    );
  }

  final private static function unescapeTokens(string $s): string {
    $result = Str::replace('@-generated', '@'.'generated', $s);
    return Str::replace(
      '@-partially-generated',
      '@'.'partially-generated',
      $result,
    );
  }
}
