#!/bin/sh
set -ex
hhvm --version

composer install

hh_client

hhvm vendor/bin/phpunit test/
hhvm vendor/bin/hhast-lint
hhvm examples/dorm/codegen.php examples/dorm/demo/DormUserSchema.php
if ! git diff --quiet; then
  echo "Demo codegen not up to date."
  exit 1
fi

echo > .hhconfig
rm -rf vendor/hhvm/hhast # avoid circular dependency when fixing things
hh_server --check $(pwd)
