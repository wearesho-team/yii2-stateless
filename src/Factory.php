<?php

namespace Wearesho\Yii\Stateless;

use yii\caching\CacheInterface;
use yii\caching\FileCache;
use yii\di\Container;
use yii\redis\Connection;
use yii\web\Session;

/**
 * Class Factory
 * @package Wearesho\Yii\Cache
 */
class Factory
{
    /** @var string */
    public $keyPrefix = '';

    /** @var Container */
    protected $container;

    /** @var Connection|null */
    protected $redis = false;

    public function __construct(Container $container)
    {
        $this->container = $container ?? \Yii::$container;
    }

    public function getRedis(): ?Connection
    {
        if ($this->redis !== false) {
            return $this->redis;
        }

        if (!$this->getEnv('REDIS_HOSTNAME') || !$this->getEnv('REDIS_DATABASE')) {
            return $this->redis = null;
        }

        return $this->redis = $this->container->get(Connection::class, [], [
            'hostname' => $this->getEnv("REDIS_HOSTNAME"),
            'database' => $this->getEnv("REDIS_DATABASE"),
            'password' => $this->getEnv("REDIS_PASSWORD") ?: null,
            'port' => $this->getEnv("REDIS_PORT") ?: 6379,
        ]);
    }

    public function getCache(Container $container, array $params, array $config): CacheInterface
    {
        if ($this->getRedis() === null) {
            return $this->container->get(FileCache::class, $params, $config);
        }

        return $this->container->get(\yii\redis\Cache::class, $params, $config + [
                'redis' => $this->getRedis(),
            ]);
    }

    public function getSession(Container $container, array $params, array $config): Session
    {
        if ($this->getRedis() === null) {
            return $this->container->get(Session::class, $params, $config);
        }

        return $this->container->get(\yii\redis\Session::class, $params, $config + [
                'redis' => $this->getRedis(),
            ]);
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
