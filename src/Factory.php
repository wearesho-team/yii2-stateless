<?php

namespace Wearesho\Yii\Stateless;

use Horat1us\Environment\MissingEnvironmentException;
use yii\caching\CacheInterface;
use yii\caching\FileCache;
use yii\di;
use yii\db;
use yii\redis;
use yii\web\Session;
use Wearesho\Yii\Stateless;

/**
 * Class Factory
 * @package Wearesho\Yii\Cache
 */
class Factory
{
    /** @var redis\Connection|null */
    protected $redis = false;

    public function getRedis(di\Container $container): ?redis\Connection
    {
        if ($this->redis !== false) {
            return $this->redis;
        }

        try {
            /** @var Stateless\Redis\ConfigInterface $config */
            $config = $container->get(Stateless\Redis\ConfigInterface::class);
        } catch (di\NotInstantiableException $exception) {
            return null;
        }

        return $container->get(redis\Connection::class, [], [
            'hostname' => $config->getHostName(),
            'database' => $config->getDataBase(),
            'password' => $config->getPassword(),
            'port' => $config->getPort(),
        ]);
    }

    public function getCache(di\Container $container, array $params, array $config): CacheInterface
    {
        if ($this->getRedis($container) === null) {
            return $container->get(FileCache::class, $params, $config);
        }

        return $container->get(\yii\redis\Cache::class, $params, $config + [
                'redis' => $this->getRedis($container),
            ]);
    }

    public function getSession(di\Container $container, array $params, array $config): Session
    {
        if ($this->getRedis($container) === null) {
            return $container->get(Session::class, $params, $config);
        }

        return $container->get(\yii\redis\Session::class, $params, $config + [
                'redis' => $this->getRedis($container),
            ]);
    }

    /**
     * @param di\Container $container
     * @param array $params
     * @param array $config
     * @return db\Connection
     * @throws \yii\base\InvalidConfigException
     * @throws di\NotInstantiableException
     * @throws MissingEnvironmentException
     */
    public function getDb(di\Container $container, array $params, array $config): db\Connection
    {
        /** @var Stateless\Db\ConfigInterface $statelessConfig */
        $statelessConfig = $container->get(Stateless\Db\ConfigInterface::class);

        $host = $statelessConfig->getHostName();
        $db = $statelessConfig->getDataBase();
        $port = $statelessConfig->getPort();

        $dsn = "pgsql:host={$host};dbname={$db};port={$port}";

        return $container->get(db\Connection::class, $params, array_merge([
            'dsn' => $dsn,
            'username' => $statelessConfig->getUserName(),
            'password' => $statelessConfig->getPassword(),
        ], $config));
    }
}
