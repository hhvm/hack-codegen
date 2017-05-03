#!/bin/sh
set -ex
hhvm --version
echo hhvm.jit=0 >> /etc/hhvm/php.ini
apt-get update -y
apt-get install -y wget curl git
curl https://getcomposer.org/installer | hhvm --php -- /dev/stdin --install-dir=/usr/local/bin --filename=composer

cd /var/source
hhvm /usr/local/bin/composer install
hh_server --check $(pwd)
hhvm vendor/bin/phpunit test/
hhvm examples/dorm/codegen.php examples/dorm/demo/DormUserSchema.php
