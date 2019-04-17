#!/bin/bash
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
if !(hhvm --version | grep -q -- -dev); then
  vendor/bin/hhast-lint
fi

# Check the codegen hasn't been modified; this is also done as part of the next
# step, but we want to make sure the verify-signatures executable works
bin/hh-codegen-verify-signatures \
  examples/dorm/demo/{DormUser.php,DormUserMutator.php}
# Check that they're up-to-date
hhvm examples/dorm/codegen.hack examples/dorm/demo/DormUserSchema.php
if ! git diff --exit-code examples/; then
  echo "Demo codegen not up to date."
  exit 1
fi
