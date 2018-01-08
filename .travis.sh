#!/bin/sh
set -ex
hhvm --version

curl https://getcomposer.org/installer | hhvm --php -- /dev/stdin --install-dir=/usr/local/bin --filename=composer

cd /var/source
hhvm /usr/local/bin/composer install

hh_server --check $(pwd)
sed -i '/enable_experimental_tc_features/d' .hhconfig
hh_server --check $(pwd)

hhvm -d hhvm.php7.all=0 vendor/bin/phpunit test/
hhvm -d hhvm.php7.all=1 vendor/bin/phpunit test/

hhvm examples/dorm/codegen.php examples/dorm/demo/DormUserSchema.php

HHVM_VERSION=$(hhvm --php -r 'echo HHVM_VERSION_ID;' 2>/dev/null);
if [ $HHVM_VERSION -ge 32300 -a $HHVM_VERSION -lt 32400 ]; then
  vendor/bin/hhast-lint
fi
