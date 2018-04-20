<?php

namespace Wearesho\Yii\Stateless\Db;

/**
 * Interface ConfigInterface
 * @package Wearesho\Yii\Stateless\Db
 */
interface ConfigInterface
{
    public function getHostName(): string;

    public function getDataBase(): string;

    public function getPort(): int;

    public function getType(): string;

    public function getUserName(): string;

    public function getPassword(): ?string;
}
