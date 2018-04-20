<?php

namespace Wearesho\Yii\Stateless;

use Horat1us\Environment\MissingEnvironmentException;
use yii\caching\CacheInterface;
use yii\caching\FileCache;
use yii\di;
use yii\db;
use yii\redis;
use yii\web;
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

        if (!$config->isAvailable()) {
            return null;
        }

        return new redis\Connection([
            'hostname' => $config->getHostName(),
            'database' => $config->getDataBase(),
            'password' => $config->getPassword(),
            'port' => $config->getPort(),
        ]);
    }

    public function getCache(di\Container $container, array $params, array $config): CacheInterface
    {
        if ($this->getRedis($container) === null) {
            return new FileCache($config);
        }

        return new redis\Cache($config + [
                'redis' => $this->getRedis($container),
            ]);
    }

    public function getSession(di\Container $container, array $params = [], array $config = []): web\Session
    {
        if ($this->getRedis($container) === null) {
            return new web\Session($config);
        }

        return new redis\Session($config + [
                'redis' => $this->getRedis($container),
            ]);
    }

    public function getWebRequest(di\Container $container, array $params = [], array $config = []): web\Request
    {
        /** @var Stateless\Request\ConfigInterface $statelessConfig */
        $statelessConfig = $container->get(Stateless\Request\ConfigInterface::class);
        return new web\Request($config + [
                'cookieValidationKey' => $statelessConfig->getCookieValidationKey(),
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
    public function getDb(di\Container $container, array $params = [], array $config = []): db\Connection
    {
        /** @var Stateless\Db\ConfigInterface $statelessConfig */
        $statelessConfig = $container->get(Stateless\Db\ConfigInterface::class);

        $host = $statelessConfig->getHostName();
        $db = $statelessConfig->getDataBase();
        $port = $statelessConfig->getPort();

        $dsn = "pgsql:host={$host};dbname={$db};port={$port}";

        return new db\Connection(array_merge([
            'dsn' => $dsn,
            'username' => $statelessConfig->getUserName(),
            'password' => $statelessConfig->getPassword(),
        ], $config));
    }
}
