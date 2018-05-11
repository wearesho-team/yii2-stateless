<?php

namespace Wearesho\Yii\Stateless\Tests;

use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;
use Wearesho\Yii\Stateless;
use yii\caching\FileCache;
use yii\db;
use yii\di;
use yii\redis;

/**
 * Class FactoryTest
 * @package Wearesho\Yii\Stateless\Tests
 */
class FactoryTest extends TestCase
{
    use PHPMock;

    /** @var Stateless\Factory */
    protected $factory;

    /** @var di\Container */
    protected $container;

    protected $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new Stateless\Factory();
        $this->container = new di\Container();

        $this->config = new class implements Stateless\Redis\ConfigInterface
        {
            /**
             * @return bool
             */
            public function isAvailable(): bool
            {
                return true;
            }

            /**
             * @return string
             */
            public function getHostName(): string
            {
                return "CharlieHostName";
            }

            /**
             * @return int
             */
            public function getDataBase(): int
            {
                return 228;
            }

            /**
             * @return null|string
             */
            public function getPassword(): ?string
            {
                return "1998";
            }

            /**
             * @return int
             */
            public function getPort(): int
            {
                return 1488;
            }

            /**
             * @return int
             */
            public function getUserName(): int
            {
                return 666;
            }
        };
    }

    public function testGetSession()
    {
        \Yii::$app = new \yii\web\Application([
            'basePath' => __DIR__,
            'id' => "some_id"
        ]);

        $this->getFunctionMock(
            'yii\web',
            'RockySession'
        )->expects($this->any())->willReturn("JohnySession");

        $this->assertEquals(
            new \yii\web\Session([]),
            $this->factory->getSession(
                $this->container, [], [])
        );

        $config = $this->config;

        $this->container->set(
            Stateless\Redis\ConfigInterface::class,
            $config
        );

        $this->assertEquals(
            new \yii\redis\Session([
                'redis' => new redis\Connection([
                    'hostname' => 'CharlieHostName',
                    'port' => 1488,
                    'password' => '1998',
                    'database' => 228
                ])
            ]),
            $this->factory->getSession(
                $this->container, [], [])
        );
    }

    public function testGetDb(): void
    {
        $config = $this->config;

        $this->container->set(
            Stateless\Db\ConfigInterface::class,
            $config
        );

        $this->assertEquals(
            new db\Connection(
                [
                    'dsn' => 'pgsql:host=CharlieHostName;dbname=228;port=1488',
                    'username' => 666,
                    'password' => '1998'
                ]
            ),
            $this->factory->getDb($this->container)
        );
    }

    public function testInstantiateRequest(): void
    {
        $config = new class implements Stateless\Request\ConfigInterface
        {
            public function getCookieValidationKey(): string
            {
                return "test-cookie-validation";
            }
        };

        $this->container->setSingleton(
            Stateless\Request\ConfigInterface::class,
            $config
        );

        $request = $this->factory->getWebRequest($this->container, [], []);

        $this->assertEquals(
            $request->cookieValidationKey,
            "test-cookie-validation"
        );
    }

    public function testGetRedis()
    {
        $this->container->set(
            Stateless\Redis\ConfigInterface::class,
            new class
            {
                public function isAvailable()
                {
                    return false;
                }
            }
        );

        $this->assertNull(
            $this->factory->getRedis($this->container)
        );

        $config = $this->config;

        $this->container->set(
            Stateless\Redis\ConfigInterface::class,
            $config
        );

        $this->assertEquals(
            new redis\Connection([
                'hostname' => 'CharlieHostName',
                'port' => 1488,
                'password' => '1998',
                'database' => 228
            ]),
            $this->factory->getRedis($this->container)
        );

        $this->container->set(
            Stateless\Redis\ConfigInterface::class,
            $config
        );

        $this->assertEquals(
            1488,
            $this->factory->getRedis($this->container)->port
        );

        $this->assertEquals(
            "CharlieHostName",
            $this->factory->getRedis($this->container)->hostname
        );

        $this->assertEquals(
            228,
            $this->factory->getRedis($this->container)->database
        );
    }

    public function testGetCache()
    {
        $this->assertInstanceOf(
            FileCache::class,
            $this->factory->getCache($this->container, [], [])
        );

        $config = $this->config;

        $this->container->set(
            Stateless\Redis\ConfigInterface::class,
            $config
        );

        $this->assertEquals(
            new redis\Cache([
                    "redis" => new redis\Connection([
                            'hostname' => 'CharlieHostName',
                            'port' => 1488,
                            'password' => '1998',
                            'database' => 228
                        ])
                ]),
            $this->factory->getCache(
                $this->container, [], [])
        );
    }
}
