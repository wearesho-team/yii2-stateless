<?php

namespace Wearesho\Yii\Stateless\Redis;

/**
 * Interface ConfigInterface
 * @package Wearesho\Yii\Stateless\Redis
 */
interface ConfigInterface
{
    public function isAvailable(): bool;

    public function getHostName(): string;

    public function getDataBase(): int;

    public function getPassword(): ?string;

    public function getPort(): int;
}
