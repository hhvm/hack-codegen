#!/bin/sh
set -ex
apt update -y
DEBIAN_FRONTEND=noninteractive apt install -y php-cli zip unzip
hhvm --version
php --version

(
  cd $(mktemp -d)
  curl https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
)
if (hhvm --version | grep -q -- -dev); then
  rm composer.lock
fi
composer install

hh_client

vendor/bin/hacktest tests/
vendor/bin/hhast-lint

hhvm examples/dorm/codegen.php examples/dorm/demo/DormUserSchema.php
if ! git diff --exit-code; then
  echo "Demo codegen not up to date."
  exit 1
fi
