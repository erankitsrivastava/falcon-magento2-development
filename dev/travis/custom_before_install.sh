#!/usr/bin/env bash

# Copyright © Magento, Inc. All rights reserved.
# See COPYING.txt for license details.

set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR

if [ $TEST_SUITE = "api-functional" ]; then
    # Install apache
    sudo apt-get update
    sudo apt-get install apache2 libapache2-mod-fastcgi
    if [ ${TRAVIS_PHP_VERSION:0:1} == "7" ]; then
        sudo cp ${TRAVIS_BUILD_DIR}/dev/travis/config/www.conf ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.d/
    fi

    # Enable php-fpm
    sudo cp ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf.default ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf
    sudo a2enmod rewrite actions fastcgi alias
    echo "cgi.fix_pathinfo = 1" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
    ~/.phpenv/versions/$(phpenv version-name)/sbin/php-fpm

    # Configure apache virtual hosts
    sudo cp -f ${TRAVIS_BUILD_DIR}/dev/travis/config/apache_virtual_host /etc/apache2/sites-available/000-default.conf
    sudo sed -e "s?%TRAVIS_BUILD_DIR%?$(pwd)?g" --in-place /etc/apache2/sites-available/000-default.conf
    sudo sed -e "s?%MAGENTO_HOST_NAME%?${MAGENTO_HOST_NAME}?g" --in-place /etc/apache2/sites-available/000-default.conf

    sudo usermod -a -G www-data travis
    sudo usermod -a -G travis www-data

    phpenv config-rm xdebug.ini
    sudo service apache2 restart

    /sbin/start-stop-daemon --start --quiet --pidfile /tmp/custom_xvfb_99.pid --make-pidfile --background --exec /usr/bin/Xvfb -- :1 -screen 0 1280x1024x24
fi