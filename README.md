# Yii2 Stateless factory
This package provides single class `Wearesho\Yii\Stateless\Factory` with:
- `getRedis` - returns Redis connection or null, depends on environment configuration
- `getSession` - return `\yii\web\Session` or `\yii\redis\Session`, if Redis available
- `getCache` - returns `\yii\caching\FileCache` or `\yii\redis\Cache`, if Redis available

Environment variables to make redis available:

- **REDIS_HOSTNAME** - required
- **REDIS_DATABASE** - required
- **REDIS_PASSWORD** - default empty
- **REDIS_PORT** - default 6379

If no environment variable provided `\yii\caching\FileCache` will be instantiated.


## Installation
`composer require wearesho-team/yii-stateless`

## Usage
```php
<?php
// your bootstrap.php file

use Wearesho\Yii\Stateless\Factory;

\Yii::$container->setSingleton(Factory::class);
$factory = \Yii::$container->get(Factory::class);

\Yii::$container->set(
    \yii\caching\CacheInterface::class,
    [$factory, 'getCache',]
);

\Yii::$container->set(
    \yii\web\Session::class,
    [$factory, 'getSession',]
);
```

```php
<?php
// your main.php file

return [
    'components' => [
        'cache' => \yii\caching\CacheInterface::class, 
        'redis' => \Yii::$container->get(Factory::class)->getRedis(),   
    ],
];
```

## License
MIT
