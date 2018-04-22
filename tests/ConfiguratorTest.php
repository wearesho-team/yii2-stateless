<?php

namespace Wearesho\Yii\Stateless\Tests;

use PHPUnit\Framework\TestCase;
use Wearesho\Yii\Stateless;
use yii\caching;
use yii\db;
use yii\di;
use yii\redis;
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

    public function testConfigure()
    {
        $this->assertEquals(
            new di\Container(),
            $this->container
        );

        Stateless\Configurator::configure($this->container);

        $this->assertTrue(
            $this->container->hasSingleton("Wearesho\Yii\Stateless\Factory")
        );
        $this->assertTrue(
            $this->container->hasSingleton("Wearesho\Yii\Stateless\Db\ConfigInterface")
        );
        $this->assertTrue(
            $this->container->hasSingleton("Wearesho\Yii\Stateless\Redis\ConfigInterface")
        );
        $this->assertTrue(
            $this->container->hasSingleton("Wearesho\Yii\Stateless\Request\ConfigInterface")
        );

        $this->assertTrue(
            $this->container->hasSingleton(caching\CacheInterface::class)
        );
        $this->assertTrue(
            $this->container->hasSingleton(db\Connection::class)
        );
        $this->assertTrue(
            $this->container->hasSingleton(web\Session::class)
        );
        $this->assertTrue(
            $this->container->hasSingleton(redis\Connection::class)
        );
        $this->assertTrue(
            $this->container->hasSingleton(web\Request::class)
        );
    }
}
