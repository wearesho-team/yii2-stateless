<?php

namespace Wearesho\Yii\Stateless\Tests;

use PHPUnit\Framework\TestCase;
use Wearesho\Yii\Stateless;
use yii\di;

/**
 * Class FactoryTest
 * @package Wearesho\Yii\Stateless\Tests
 */
class FactoryTest extends TestCase
{
    /** @var Stateless\Factory */
    protected $factory;

    /** @var di\Container */
    protected $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new Stateless\Factory();
        $this->container = new di\Container();
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

        $this->container->setSingleton(Stateless\Request\ConfigInterface::class, $config);
        $request = $this->factory->getWebRequest($this->container, [], []);

        $this->assertEquals(
            $request->cookieValidationKey,
            "test-cookie-validation"
        );
    }
}
