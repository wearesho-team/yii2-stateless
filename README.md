# Yii2 Stateless

[![Latest Stable Version](https://poser.pugx.org/wearesho-team/yii2-stateless/v/stable.png)](https://packagist.org/packages/wearesho-team/yii2-stateless)
[![Total Downloads](https://poser.pugx.org/wearesho-team/yii2-stateless/downloads.png)](https://packagist.org/packages/wearesho-team/yii2-stateless)
[![Build Status](https://travis-ci.org/wearesho-team/yii2-stateless.svg?branch=master)](https://travis-ci.org/wearesho-team/yii2-stateless)
[![codecov](https://codecov.io/gh/wearesho-team/yii2-stateless/branch/master/graph/badge.svg)](https://codecov.io/gh/wearesho-team/yii2-stateless)

This package provides single class `Wearesho\Yii\Stateless\Factory` with:
- `getRedis` - returns Redis connection or null, depends on [Redis\ConfigInterface](./src/Db/ConfigInterface.php)
- `getSession` - return `\yii\web\Session` or `\yii\redis\Session`, if Redis available
- `getCache` - returns `\yii\caching\FileCache` or `\yii\redis\Cache`, if Redis available
- `getDb` - returns `\yii\db\Connection` depends on [Db\ConfigInterface](./src/Redis/ConfigInterface.php)

Note:
- [Redis\ConfigInterface](./src/Redis/ConfigInterface.php) have [Redis\EnvironmentConfig](./src/Redis/EnvironmentConfig.php) implementation

Environment variables to make redis available:

- **REDIS_HOSTNAME** - required
- **REDIS_DATABASE** - required
- **REDIS_PASSWORD** - default empty
- **REDIS_PORT** - default 6379

- [Db\ConfigInterface](./src/Db/ConfigInterface.php) have [Db\EnvironmentConfig](./src/Db/EnvironmentConfig.php) implementation

Environment variables to instantiate database:

- **DB_HOST** - database host or ip
- **DB_NAME** - database name
- **DB_USER** - database user 
- **DB_PORT** - port for connection (default *3306* for MySQL, *5432* for PostgreSQL)
- **DB_TYPE** - type of database (default *pgsql*)
- **DB_PASSWORD** - database user password (default *null*)

**You can copy [.env.example](./.env.example) to your project**

## Installation
`composer require wearesho-team/yii-stateless:^3.0`

## Usage
```php
<?php
// your bootstrap.php file

use Wearesho\Yii\Stateless;

Stateless\Configurator::configure(\Yii::$container);
```

```php
<?php
// your main.php file
use Wearesho\Yii\Stateless;

$config = [
    // Your Application configuration 
];

return array_merge(Stateless\Configurator::config(\Yii::$container), $config);
```

For advanced usage 

## License
MIT
