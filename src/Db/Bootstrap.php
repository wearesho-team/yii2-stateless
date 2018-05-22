<?php

namespace Wearesho\Yii\Stateless\Db;

use Horat1us\Environment\MissingEnvironmentException;
use yii\base;
use yii\di;
use yii\db;

/**
 * Class Bootstrap
 * @package Db
 */
class Bootstrap implements base\BootstrapInterface
{
    /**
     * @param base\Application $app
     * @throws base\InvalidConfigException
     */
    public function bootstrap($app): void
    {
        $container = \Yii::$container;
        $this->configure($container);

        if (!$app->has('db')) {
            $app->set('db', [
                'class' => db\Connection::class,
            ]);
        }
    }

    /**
     * If ConfigInterface did not configured it will be configured to EnvironmentConfig
     *
     * @param di\Container $container
     */
    public function configure(di\Container $container): void
    {
        if (!$container->has(ConfigInterface::class)) {
            $container->set(ConfigInterface::class, EnvironmentConfig::class);
        }

        $container->set(
            db\Connection::class,
            function (
                di\Container $container,
                /** @noinspection PhpUnusedParameterInspection */
                array $params,
                array $baseConfig
            ): db\Connection {
                /** @var ConfigInterface $config */
                $config = $container->get(ConfigInterface::class);

                try {
                    $host = $config->getHostName();
                    $db = $config->getDataBase();
                    $port = $config->getPort();
                    $username = $config->getUserName();
                    $password = $config->getPassword();
                    $type = $config->getType();
                } catch (MissingEnvironmentException $exception) {
                    throw new di\NotInstantiableException($exception->getMessage(), 0, $exception);
                }

                $dsn = $type . ":host={$host};dbname={$db};port={$port}";


                return new db\Connection($baseConfig + [
                        'dsn' => $dsn,
                        'username' => $username,
                        'password' => $password,
                    ]);
            }
        );
    }
}
