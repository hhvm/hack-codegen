#!/bin/sh
set -ex
hhvm --version

composer install

hh_client

hhvm vendor/bin/phpunit test/

composer install --no-dev
echo > .hhconfig
hh_server --check $(pwd)
