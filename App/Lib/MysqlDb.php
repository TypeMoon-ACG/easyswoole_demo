<?php

namespace App\Lib;

use EasySwoole\Component\Singleton;

class MysqlDb
{
    use Singleton;

    public $client = "";

    public function __construct()
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

        try {
            $this->client = new \EasySwoole\Mysqli\Client($config);
            // $res = $this->client->queryBuilder()->get('video');
            // var_dump($this->client->execBuilder());
            if (empty($this->client)) {
                throw new \Exception("Mysql Not Found!!");
            }
        } catch (\Exception $e) {
            throw new \Exception("Mysql连接失败!");
        }
    }


    public function __call($name, $arguments) {
		
		// var_dump(...$arguments);
		return $this->client->$name(...$arguments);
	}

}
