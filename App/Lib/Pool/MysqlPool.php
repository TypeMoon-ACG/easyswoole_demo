<?php
namespace App\Lib\Pool;

use EasySwoole\Pool\Config as PoolConfig;
use EasySwoole\Pool\AbstractPool;
use EasySwoole\Mysqli\Client;

class MysqlPool extends AbstractPool
{

    protected $mysql_config = [
        'host'          => '127.0.0.1',
            'port'          => 22331,
            'user'          => 'printemps',
            'password'      => 'lovelive',
            'database'      => 'easyswoole',
            'timeout'       => 5,
            'charset'       => 'utf8mb4',
    ];

    /**
     * 重写构造函数,为了传入redis配置
     * RedisPool constructor.
     * @param Config      $conf
     * @param RedisConfig $redisConfig
     * @throws \EasySwoole\Pool\Exception\Exception
     */
    public function __construct(PoolConfig $conf)
    {
        parent::__construct($conf);
    }

    protected function createObject()
    {

        $config = new \EasySwoole\Mysqli\Config([
            'host'          => '127.0.0.1',
            'port'          => 22331,
            'user'          => 'printemps',
            'password'      => 'lovelive',
            'database'      => 'easyswoole',
            'timeout'       => 5,
            'charset'       => 'utf8mb4',
        ]);

        $client = new \EasySwoole\Mysqli\Client($config);
        return $client;
    }

}
