#!/usr/bin/env bash
cp dev/travis/config/static_php_common.txt dev/tests/static/testsuite/Magento/Test/Php/_files/whitelist/common.txt
cat dev/travis/config/phpcpd_blacklist.txt >> dev/tests/static/testsuite/Magento/Test/Php/_files/phpcpd/blacklist/common.txt