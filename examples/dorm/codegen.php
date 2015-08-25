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

require_once('vendor/autoload.php');
require_once('core/DormSchema.php');
require_once('core/DormField.php');
require_once('CodegenDorm.php');
require_once('CodegenMutator.php');

if ($argc == 1) {
  echo "  Usage: ".$argv[0]." file_name.php\n\n";
  exit(1);
}
$fname = $argv[1];
if (!file_exists($fname)) {
  echo "  File doesn't exist: $fname\n\n";
  exit(1);
}

$classes = get_declared_classes();
require_once($fname);
$new_classes = array_diff(get_declared_classes(), $classes);


foreach($new_classes as $class_name) {
  $ref = new \ReflectionClass($class_name);
  if ($ref->isAbstract()) {
    continue;
  }
  $instance = $ref->newInstance();
  if (!$instance instanceof DormSchema) {
    continue;
  }
  echo "Generating code for $class_name\n";
  (new CodegenDorm($instance))->generate();
  (new CodegenMutator($instance))->generate();
}
