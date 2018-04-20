<?php

namespace Wearesho\Yii\Stateless\Request;

use Horat1us\Environment;

/**
 * Class EnvironmentConfig
 * @package Wearesho\Yii\Stateless\Request
 */
class EnvironmentConfig extends Environment\Config implements ConfigInterface
{
    public function getCookieValidationKey(): string
    {
        return $this->getEnv("COOKIE_VALIDATION_KEY");
    }
}
