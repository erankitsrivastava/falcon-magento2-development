#!/usr/bin/env bash

# Copyright © Magento, Inc. All rights reserved.
# See COPYING.txt for license details.

set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR

# prepare for test suite
case $TEST_SUITE in
    api-functional)
        cd dev/tests/api-functional

        # create database and move db config into place
        mysql -uroot -e '
            SET @@global.sql_mode = NO_ENGINE_SUBSTITUTION;
            CREATE DATABASE magento_api_tests;
        '
        sed -e "s?127.0.0.1:80?${MAGENTO_HOST_NAME}?g" --in-place ./phpunit.xml
        mv config/install-config-mysql.travis.php.dist config/install-config-mysql.php
        echo "==> testsuite preparation complete"
        cd ../../..
        ;;
esac
