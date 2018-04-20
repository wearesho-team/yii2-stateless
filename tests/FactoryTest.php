<?php

namespace Wearesho\Yii\Stateless\Tests;

use PHPUnit\Framework\TestCase;
use Wearesho\Yii\Stateless;
use yii\caching\FileCache;
use yii\di;
use yii\redis;

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

    public function testGetRedis()
    {
        $this->assertEquals(
            null,
            $this->factory->getRedis($this->container)
        );

        $config = new class implements Stateless\Redis\ConfigInterface
        {

            public function isAvailable(): bool
            {
                return false;
            }

            /**
             * @return string
             * @throws Environment\MissingEnvironmentException
             */
            public function getHostName(): string
            {
                return "";
            }

            /**
             * @return int
             * @throws Environment\MissingEnvironmentException
             */
            public function getDataBase(): int
            {
                return null;
            }

            public function getPassword(): ?string
            {
                return null;
            }

            public function getPort(): int
            {
                return null;
            }
        };

        $this->container->set(Stateless\Redis\ConfigInterface::class,
            $config
        );

        $this->assertEquals(
            null,
            $this->factory->getRedis($this->container)
        );


        $config = new class implements Stateless\Redis\ConfigInterface
        {

            public function isAvailable(): bool
            {
                return true;
            }

            /**
             * @return string
             * @throws Environment\MissingEnvironmentException
             */
            public function getHostName(): string
            {
                return "";
            }

            /**
             * @return int
             * @throws Environment\MissingEnvironmentException
             */
            public function getDataBase(): int
            {
                return 0;
            }

            public function getPassword(): ?string
            {
                return null;
            }

            public function getPort(): int
            {
                return 0;
            }
        };

        $this->container->set(Stateless\Redis\ConfigInterface::class,
            $config
        );

        $array = $this->factory->getRedis($this->container);

        $this->assertEquals(
            new redis\Connection([
                'hostname' => '',
                'port' => 0,
                'unixSocket' => null,
                'password' => null,
                'database' => 0,
                'connectionTimeout' => null,
                'dataTimeout' => null,
                'socketClientFlags' => 4,
                'retries' => 0,
                'redisCommands' => [
                    0 => 'APPEND',
                    1 => 'AUTH',
                    2 => 'BGREWRITEAOF',
                    3 => 'BGSAVE',
                    4 => 'BITCOUNT',
                    5 => 'BITFIELD',
                    6 => 'BITOP',
                    7 => 'BITPOS',
                    8 => 'BLPOP',
                    9 => 'BRPOP',
                    10 => 'BRPOPLPUSH',
                    11 => 'CLIENT KILL',
                    12 => 'CLIENT LIST',
                    13 => 'CLIENT GETNAME',
                    14 => 'CLIENT PAUSE',
                    15 => 'CLIENT REPLY',
                    16 => 'CLIENT SETNAME',
                    17 => 'CLUSTER ADDSLOTS',
                    18 => 'CLUSTER COUNTKEYSINSLOT',
                    19 => 'CLUSTER DELSLOTS',
                    20 => 'CLUSTER FAILOVER',
                    21 => 'CLUSTER FORGET',
                    22 => 'CLUSTER GETKEYSINSLOT',
                    23 => 'CLUSTER INFO',
                    24 => 'CLUSTER KEYSLOT',
                    25 => 'CLUSTER MEET',
                    26 => 'CLUSTER NODES',
                    27 => 'CLUSTER REPLICATE',
                    28 => 'CLUSTER RESET',
                    29 => 'CLUSTER SAVECONFIG',
                    30 => 'CLUSTER SETSLOT',
                    31 => 'CLUSTER SLAVES',
                    32 => 'CLUSTER SLOTS',
                    33 => 'COMMAND',
                    34 => 'COMMAND COUNT',
                    35 => 'COMMAND GETKEYS',
                    36 => 'COMMAND INFO',
                    37 => 'CONFIG GET',
                    38 => 'CONFIG REWRITE',
                    39 => 'CONFIG SET',
                    40 => 'CONFIG RESETSTAT',
                    41 => 'DBSIZE',
                    42 => 'DEBUG OBJECT',
                    43 => 'DEBUG SEGFAULT',
                    44 => 'DECR',
                    45 => 'DECRBY',
                    46 => 'DEL',
                    47 => 'DISCARD',
                    48 => 'DUMP',
                    49 => 'ECHO',
                    50 => 'EVAL',
                    51 => 'EVALSHA',
                    52 => 'EXEC',
                    53 => 'EXISTS',
                    54 => 'EXPIRE',
                    55 => 'EXPIREAT',
                    56 => 'FLUSHALL',
                    57 => 'FLUSHDB',
                    58 => 'GEOADD',
                    59 => 'GEOHASH',
                    60 => 'GEOPOS',
                    61 => 'GEODIST',
                    62 => 'GEORADIUS',
                    63 => 'GEORADIUSBYMEMBER',
                    64 => 'GET',
                    65 => 'GETBIT',
                    66 => 'GETRANGE',
                    67 => 'GETSET',
                    68 => 'HDEL',
                    69 => 'HEXISTS',
                    70 => 'HGET',
                    71 => 'HGETALL',
                    72 => 'HINCRBY',
                    73 => 'HINCRBYFLOAT',
                    74 => 'HKEYS',
                    75 => 'HLEN',
                    76 => 'HMGET',
                    77 => 'HMSET',
                    78 => 'HSET',
                    79 => 'HSETNX',
                    80 => 'HSTRLEN',
                    81 => 'HVALS',
                    82 => 'INCR',
                    83 => 'INCRBY',
                    84 => 'INCRBYFLOAT',
                    85 => 'INFO',
                    86 => 'KEYS',
                    87 => 'LASTSAVE',
                    88 => 'LINDEX',
                    89 => 'LINSERT',
                    90 => 'LLEN',
                    91 => 'LPOP',
                    92 => 'LPUSH',
                    93 => 'LPUSHX',
                    94 => 'LRANGE',
                    95 => 'LREM',
                    96 => 'LSET',
                    97 => 'LTRIM',
                    98 => 'MGET',
                    99 => 'MIGRATE',
                    100 => 'MONITOR',
                    101 => 'MOVE',
                    102 => 'MSET',
                    103 => 'MSETNX',
                    104 => 'MULTI',
                    105 => 'OBJECT',
                    106 => 'PERSIST',
                    107 => 'PEXPIRE',
                    108 => 'PEXPIREAT',
                    109 => 'PFADD',
                    110 => 'PFCOUNT',
                    111 => 'PFMERGE',
                    112 => 'PING',
                    113 => 'PSETEX',
                    114 => 'PSUBSCRIBE',
                    115 => 'PUBSUB',
                    116 => 'PTTL',
                    117 => 'PUBLISH',
                    118 => 'PUNSUBSCRIBE',
                    119 => 'QUIT',
                    120 => 'RANDOMKEY',
                    121 => 'READONLY',
                    122 => 'READWRITE',
                    123 => 'RENAME',
                    124 => 'RENAMENX',
                    125 => 'RESTORE',
                    126 => 'ROLE',
                    127 => 'RPOP',
                    128 => 'RPOPLPUSH',
                    129 => 'RPUSH',
                    130 => 'RPUSHX',
                    131 => 'SADD',
                    132 => 'SAVE',
                    133 => 'SCARD',
                    134 => 'SCRIPT DEBUG',
                    135 => 'SCRIPT EXISTS',
                    136 => 'SCRIPT FLUSH',
                    137 => 'SCRIPT KILL',
                    138 => 'SCRIPT LOAD',
                    139 => 'SDIFF',
                    140 => 'SDIFFSTORE',
                    141 => 'SELECT',
                    142 => 'SET',
                    143 => 'SETBIT',
                    144 => 'SETEX',
                    145 => 'SETNX',
                    146 => 'SETRANGE',
                    147 => 'SHUTDOWN',
                    148 => 'SINTER',
                    149 => 'SINTERSTORE',
                    150 => 'SISMEMBER',
                    151 => 'SLAVEOF',
                    152 => 'SLOWLOG',
                    153 => 'SMEMBERS',
                    154 => 'SMOVE',
                    155 => 'SORT',
                    156 => 'SPOP',
                    157 => 'SRANDMEMBER',
                    158 => 'SREM',
                    159 => 'STRLEN',
                    160 => 'SUBSCRIBE',
                    161 => 'SUNION',
                    162 => 'SUNIONSTORE',
                    163 => 'SWAPDB',
                    164 => 'SYNC',
                    165 => 'TIME',
                    166 => 'TOUCH',
                    167 => 'TTL',
                    168 => 'TYPE',
                    169 => 'UNSUBSCRIBE',
                    170 => 'UNLINK',
                    171 => 'UNWATCH',
                    172 => 'WAIT',
                    173 => 'WATCH',
                    174 => 'ZADD',
                    175 => 'ZCARD',
                    176 => 'ZCOUNT',
                    177 => 'ZINCRBY',
                    178 => 'ZINTERSTORE',
                    179 => 'ZLEXCOUNT',
                    180 => 'ZRANGE',
                    181 => 'ZRANGEBYLEX',
                    182 => 'ZREVRANGEBYLEX',
                    183 => 'ZRANGEBYSCORE',
                    184 => 'ZRANK',
                    185 => 'ZREM',
                    186 => 'ZREMRANGEBYLEX',
                    187 => 'ZREMRANGEBYRANK',
                    188 => 'ZREMRANGEBYSCORE',
                    189 => 'ZREVRANGE',
                    190 => 'ZREVRANGEBYSCORE',
                    191 => 'ZREVRANK',
                    192 => 'ZSCORE',
                    193 => 'ZUNIONSTORE',
                    194 => 'SCAN',
                    195 => 'SSCAN',
                    196 => 'HSCAN',
                    197 => 'ZSCAN',
                ],
            ]),
            $this->factory->getRedis($this->container)
        );
    }

    public function testGetCache()
    {
        $this->assertEquals(
            new FileCache(),
            $this->factory->getCache($this->container, [], [

            ])
        );

        $config = new class implements Stateless\Redis\ConfigInterface
        {

            public function isAvailable(): bool
            {
                return true;
            }

            /**
             * @return string
             * @throws Environment\MissingEnvironmentException
             */
            public function getHostName(): string
            {
                return "";
            }

            /**
             * @return int
             * @throws Environment\MissingEnvironmentException
             */
            public function getDataBase(): int
            {
                return 0;
            }

            public function getPassword(): ?string
            {
                return null;
            }

            public function getPort(): int
            {
                return 0;
            }
        };

        $this->container->set(Stateless\Redis\ConfigInterface::class,
            $config
        );

        $this->assertEquals(
            new redis\Cache([
                "redis" => new redis\Connection([
                    'hostname' => '',
                    'port' => 0
                ])
            ]),
            $this->factory->getCache($this->container, [], [

            ])
        );
    }

    public function testGetSession()
    {
        $this->assertEquals(
            new \yii\web\Session,
            $this->factory->getSession($this->container)
        );

        $config = new class implements Stateless\Redis\ConfigInterface
        {
            public function isAvailable(): bool
            {
                return true;
            }

            /**
             * @return string
             * @throws Environment\MissingEnvironmentException
             */
            public function getHostName(): string
            {
                return "";
            }

            /**
             * @return int
             * @throws Environment\MissingEnvironmentException
             */
            public function getDataBase(): int
            {
                return 0;
            }

            public function getPassword(): ?string
            {
                return null;
            }

            public function getPort(): int
            {
                return 0;
            }
        };

        $this->container->set(Stateless\Redis\ConfigInterface::class,
            $config
        );

        $this->assertEquals(
            new \yii\web\Session([
            ]),
            $this->factory->getSession($this->container,[],[
                    "redis" => new redis\Connection()
            ])
        );
    }
}
