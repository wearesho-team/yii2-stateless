<?php

namespace Wearesho\Yii\Stateless\Tests;

use PHPUnit\Framework\TestCase;
use Wearesho\Yii\Stateless;

use yii\di;
use yii\redis;
use yii\db;
use yii\caching;
use yii\web;

/**
 * Class ConfiguratorTest
 * @package Wearesho\Yii\Stateless\Tests
 */
class ConfiguratorTest extends TestCase
{
    /** @var di\Container */
    protected $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = new di\Container();
    }

    public function testConfigWithoutRedis(): void
    {
        $this->container->setSingleton(
            Stateless\Factory::class,
            new class extends Stateless\Factory
            {
                public function getRedis(di\Container $container): ?redis\Connection
                {
                    return null;
                }
            }
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $config = Stateless\Configurator::config($this->container);
        $this->assertEquals(
            [
                'components' => [
                    'db' => [
                        'class' => db\Connection::class,
                    ],
                    'session' => [
                        'class' => web\Session::class,
                    ],
                    'cache' => [
                        'class' => caching\CacheInterface::class,
                    ],
                ]
            ],
            $config
        );
    }

    public function testConfigWithRedis(): void
    {

        $this->container->setSingleton(
            Stateless\Factory::class,
            new class extends Stateless\Factory
            {
                public function getRedis(di\Container $container): ?redis\Connection
                {
                    return new redis\Connection();
                }
            }
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $config = Stateless\Configurator::config($this->container);

        $this->assertEquals(
            [
                'components' => [
                    'db' => [
                        'class' => db\Connection::class,
                    ],
                    'session' => [
                        'class' => web\Session::class,
                    ],
                    'cache' => [
                        'class' => caching\CacheInterface::class,
                    ],
                    'redis' => [
                        'class' => redis\Connection::class,
                    ]
                ]
            ],
            $config
        );
    }
}
