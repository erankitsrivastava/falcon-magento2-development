# Deity MagentoApi development
[![Build Status](https://travis-ci.org/deity-io/falcon-magento2-development.svg?branch=develop)](https://travis-ci.org/deity-io/falcon-magento2-development)
<br/>
Dedicated module repo: https://github.com/deity-io/falcon-magento2-module.<br/>

[Magento 2.2](https://github.com/deity-io/falcon-magento2-development/tree/develop-m2.2) [![Build Status](https://travis-ci.org/deity-io/falcon-magento2-development.svg?branch=develop-m2.2)](https://travis-ci.org/deity-io/falcon-magento2-development)

This repository is for module development.

## Getting Started

Make sure you have your access keys ready for  `repo.magento.com` and `github`.

### Prerequisites

Deity Magento module is compatible with magento version 2.2+ CE and EE versions. 
Full scale support of versions 2.0.* and 2.1.* is not our priority.
However if you encounter an issue running with magento version lower than 2.2 feel free
to open an issue or reach out to our support channel.
```
magento >= 2.2
```

### Installing

Installing deity magento module is similar to installing any module for Magento 2 platform 

```
git clone git@github.com:deity-io/falcon-magento2-development.git magento_folder
composer install
```
When the magento is setup, create one extra magento admin user for Deity Falcon to connect

```
bin/magento  admin:user:create  --admin-user='your-admin-username' --admin-password='your-admin-password' --admin-email='admin@deity.test' --admin-firstname='node' --admin-lastname='Deity'
```

## Branch structure.
Stable version is [master](../../tree/master) branch. <br/>
All the features which are prepared for release getting merged to [develop](../../tree/develop) branch. <br/> 
Also there is a branch for testing back-compatibility with [Magento 2.2](../../tree/develop-m2.2)

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/deity-io/falcon-magento2-development/tags). 

## License

This project is licensed under Open Software License ("OSL") v. 3.0 - see the [LICENSE.md](LICENSE.md) file for details
