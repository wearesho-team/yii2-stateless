<?php

namespace Wearesho\Yii\Stateless;

use yii\di;
use yii\db;
use yii\web;
use yii\caching;
use yii\redis;

use Wearesho\Yii\Stateless;

/**
 * Class Configurator
 * Provides easy-to-use configuration for Yii2 application
 *
 * @package Wearesho\Yii\Stateless
 */
class Configurator
{
    /**
     * Configures \yii\di\Container with default values
     * Should not be used with advanced application configurations
     *
     * @param di\Container $container
     * @throws \yii\base\InvalidConfigException
     * @throws di\NotInstantiableException
     */
    public static function configure(di\Container $container): void
    {
        $container->setSingleton(Stateless\Factory::class);
        /** @var Stateless\Factory $factory */
        $factory = $container->get(Stateless\Factory::class);

        if (!$container->has(Stateless\Db\ConfigInterface::class)) {
            $container->setSingleton(
                Stateless\Db\ConfigInterface::class,
                Stateless\Db\EnvironmentConfig::class
            );
        }

        if (!$container->has(Stateless\Redis\ConfigInterface::class)) {
            $container->setSingleton(
                Stateless\Redis\ConfigInterface::class,
                Stateless\Redis\EnvironmentConfig::class
            );
        }

        $container->setSingleton(
            caching\CacheInterface::class,
            [$factory, 'getCache']
        );
        $container->setSingleton(
            db\Connection::class,
            [$factory, 'getDb']
        );
        $container->setSingleton(
            web\Session::class,
            [$factory, 'getSession']
        );
        $container->setSingleton(
            redis\Connection::class,
            [$factory, 'getRedis']
        );
    }

    /**
     * Returns part of \yii\base\Application configuration
     *
     * @param di\Container $container
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws di\NotInstantiableException
     */
    public static function config(di\Container $container): array
    {
        /** @var Stateless\Factory $factory */
        $factory = $container->get(Stateless\Factory::class);

        $components = [
            'db' => [
                'class' => db\Connection::class,
            ],
            'session' => [
                'class' => web\Session::class,
            ],
            'cache' => [
                'class' => caching\CacheInterface::class,
            ],
        ];

        if ($factory->getRedis($container) !== null) {
            $components['redis'] = [
                'class' => redis\Connection::class,
            ];
        }

        return ['components' => $components];
    }
}
