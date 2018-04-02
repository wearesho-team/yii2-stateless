# Yii2 Cache Factory
This package provides single class `Wearesho\Yii\Cache` with method `instantiate`
that returns `\yii\caching\CacheInterface` instance.

It will return `\yii\redis\Cache` if environment variables provided:

- REDIS_HOSTNAME
- REDIS_DATABASE
- REDIS_PASSWORD
- REDIS_PORT

If no environment variable provided `\yii\caching\FileCache` will be instantiated.


## Installation
`composer require wearesho-team/yii-cache-factory`

## Usage
```php
<?php
// your bootstrap.php file

\Yii::$container->set(
    \yii\caching\CacheInterface::class,
    function(): \yii\caching\CacheInterface {
        /** @var \Wearesho\Yii\Cache\CacheFactory $factory */
        $factory = \Yii::$container->get(\Wearesho\Yii\Cache\CacheFactory::class);
        return $factory->instantiate();
    }
);
```

```php
<?php
// your main.php file

return [
    'components' => [
        'cache' => \yii\caching\CacheInterface::class,    
    ],
];
```

## License
Proprietary. Contact [Wearesho Team](https://wearesho.com) for usage.  
Part of [Bobra Credit System](https://bobra.io) 