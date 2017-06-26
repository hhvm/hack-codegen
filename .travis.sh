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
if [ $(hhvm --php -r 'echo HHVM_VERSION_ID;' 2>/dev/null) -ge 32002 ]; then
  hhvm -d hhvm.php7.all=1 -d vendor/bin/phpunit test/
fi
hhvm examples/dorm/codegen.php examples/dorm/demo/DormUserSchema.php
