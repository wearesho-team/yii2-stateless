<?php

namespace Wearesho\Yii\Stateless\Request;

/**
 * Interface ConfigInterface
 * @package Wearesho\Yii\Stateless\Request
 */
interface ConfigInterface
{
    public function getCookieValidationKey(): string;
}
