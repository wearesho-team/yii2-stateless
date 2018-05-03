<?php

namespace Wearesho\Yii\Stateless\Db;

use Horat1us\Environment;

/**
 * Class EnvironmentConfig
 * @package Wearesho\Yii\Stateless\Db
 */
class EnvironmentConfig extends Environment\Config implements ConfigInterface
{
    /**
     * @return string
     * @throws Environment\MissingEnvironmentException
     */
    public function getHostName(): string
    {
        return $this->getEnv("DB_HOST");
    }

    /**
     * @return string
     * @throws Environment\MissingEnvironmentException
     */
    public function getDataBase(): string
    {
        return $this->getEnv("DB_NAME");
    }

    /**
     * @return int
     * @throws Environment\MissingEnvironmentException
     */
    public function getPort(): int
    {
        $defaultPort = false;

        switch ($this->getType()) {
            case 'pgsql':
                $defaultPort = 5432;
                break;
            case 'mysql':
                $defaultPort = 3306;
        }

        return $this->getEnv('DB_PORT', $defaultPort);
    }

    public function getType(): string
    {
        return $this->getEnv('DB_TYPE', 'pgsql');
    }

    /**
     * @return string
     * @throws Environment\MissingEnvironmentException
     */
    public function getUserName(): string
    {
        return $this->getEnv('DB_USER');
    }

    public function getPassword(): ?string
    {
        return $this->getEnv('DB_PASSWORD', null);
    }
}
