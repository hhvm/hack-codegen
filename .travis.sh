#!/bin/sh
set -ex
hhvm --version

composer install

hh_client

hhvm vendor/bin/phpunit test/

echo > .hhconfig
hh_server --check $(pwd)
