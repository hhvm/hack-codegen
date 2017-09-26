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
