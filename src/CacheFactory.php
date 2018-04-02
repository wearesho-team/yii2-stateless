<?php

namespace Wearesho\Yii\Cache;

use yii\base\BaseObject;
use yii\caching\CacheInterface;
use yii\caching\FileCache;
use yii\di\Container;

/**
 * Class CacheFactory
 * @package Wearesho\Yii\Cache
 */
class CacheFactory extends BaseObject
{
    /**
     * Will be used as prefix for environment variables
     * @var string
     */
    public $keyPrefix = "";

    /** @var Container */
    protected $container;

    public function __construct(Container $container, array $config = [])
    {
        parent::__construct($config);
        $this->container = $container;
    }

    /**
     * @return CacheInterface
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function instantiate(): CacheInterface
    {
        if ($this->getEnv("REDIS_HOSTNAME") !== false) {

            /** @var \yii\redis\Cache $cache */
            $cache = $this->container->get(\yii\redis\Cache::class, [
                'redis' => [
                    'class' => \yii\redis\Connection::class,
                    'hostname' => $this->getEnv("REDIS_HOSTNAME"),
                    'database' => $this->getEnv("REDIS_DATABASE"),
                    'password' => $this->getEnv("REDIS_PASSWORD"),
                    'port' => $this->getEnv("REDIS_PORT"),
                ],
            ]);
            return $cache;
        }

        return $this->container->get(FileCache::class);
    }

    /**
     * @param string $key
     * @return false|string
     */
    protected function getEnv(string $key)
    {
        return getenv($this->keyPrefix . $key);
    }
}
