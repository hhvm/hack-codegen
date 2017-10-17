#!/bin/sh
set -ex
hhvm --version

curl https://getcomposer.org/installer | hhvm --php -- /dev/stdin --install-dir=/usr/local/bin --filename=composer

cd /var/source
hhvm /usr/local/bin/composer install

hh_server --check $(pwd)

hhvm vendor/bin/phpunit test/
hhvm -d hhvm.php7.all=1 vendor/bin/phpunit test/

hhvm examples/dorm/codegen.php examples/dorm/demo/DormUserSchema.php
vendor/bin/hhast-lint

HHVM_VERSION=$(hhvm --php -r 'echo HHVM_VERSION_ID;' 2>/dev/null);
if [ $HHVM_VERSION -ge 32200 -a $HHVM_VERSION -lt 32300 ]; then
  echo enable_experimental_tc_features = optional_shape_field, unknown_fields_shape_is_not_subtype_of_known_fields_shape >> .hhconfig
  hh_server --check $(pwd)
fi
