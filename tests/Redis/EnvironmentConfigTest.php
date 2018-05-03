<?php

namespace Wearesho\Yii\Stateless\Tests\Redis;

use PHPUnit\Framework\TestCase;
use Wearesho\Yii\Stateless\Redis;

/**
 * Class EnvironmentConfig
 * @package Wearesho\Yii\Stateless\Tests\Redis
 */
class EnvironmentConfigTest extends TestCase
{
    /** @var Redis\EnvironmentConfig */
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new Redis\EnvironmentConfig();
    }

    public function testDefaultPassword(): void
    {
        putenv("REDIS_PASSWORD");
        $this->assertNull($this->config->getPassword(), "Default password should be null");
    }
}
