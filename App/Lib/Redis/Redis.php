<?php

namespace App\Lib\Redis;

use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Config;

class Redis
{

    use Singleton;

    public $redis = "";

    private function __construct()
    {
        if (!extension_loaded("redis")) {
            throw new \Exception("Redis Extension not found!!");
        }
        $conf = Config::getInstance()->getConf("REDIS_CONF");
        try {
            $this->redis = new \Redis();
            $result = $this->redis->connect($conf['host'], $conf['port'], $conf['timeout']);
            $this->redis->auth($conf['auth']);
        } catch (\Exception $e) {
            throw new \Exception("Redis连接失败!");
        }
    }

    
    public function get($key)
    {
        if (empty($key)) return "";
        return $this->redis->get($key);
    }

    public function lpop($key)
    {
        if (empty($key)) return "";
        return $this->redis->lpop($key);
    }

    public function rpush($key, $val)
    {
        if (empty($key) || empty($val)) return "";
        return $this->redis->rpush($key, $val);
    }

    // public function zincrby($key, $number, $member) {
	// 	if(empty($key) || empty($member)) {
	// 		return false;
	// 	}

	// 	return $this->redis->zincrby($key, $number, $member);
	// }

    /**
	 * 当类中不存在该方法时候，直接调用call 实现调用底层redis相关的方法
	 * @auth   singwa
	 * @param  [type] $name      [description]
	 * @param  [type] $arguments [description]
	 * @return [type]            [description]
	 */
	public function __call($name, $arguments) {
		
		// var_dump(...$arguments);
		return $this->redis->$name(...$arguments);
	}

}
