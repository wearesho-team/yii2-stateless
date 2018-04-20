<?php

namespace Wearesho\Yii\Stateless\Redis;

use Horat1us\Environment;

/**
 * Class EnvironmentConfig
 * @package Wearesho\Yii\Stateless\Redis
 */
class EnvironmentConfig extends Environment\Config implements ConfigInterface
{
    public function isAvailable(): bool
    {
        try {
            $this->getHostName();
            $this->getDataBase();
        } catch (Environment\MissingEnvironmentException $exception) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     * @throws Environment\MissingEnvironmentException
     */
    public function getHostName(): string
    {
        return $this->getEnv("REDIS_HOSTNAME");
    }

    /**
     * @return int
     * @throws Environment\MissingEnvironmentException
     */
    public function getDataBase(): int
    {
        return $this->getEnv("REDIS_DATABASE", 1);
    }

    public function getPassword(): ?string
    {
        return $this->getEnv("REDIS_PASSWORD", null);
    }

    public function getPort(): int
    {
        return $this->getEnv("REDIS_PORT", 6379);
    }
}
