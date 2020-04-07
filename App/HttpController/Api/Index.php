<?php

namespace App\HttpController\Api;

use App\HttpController\Based;
use App\Lib\Redis\Redis;
use App\Model\OrmTest;
use EasySwoole\Component\Di;

class Index extends Based
{
    
    
    public function video()
    {
        // easyswoole 升级 composer require easyswoole/easyswoole
        $data = [
            "id" => "1",
            "name" => "LoveLive!!23333!!",
            "created" => time(),
        ];
        
        return $this->writeJson(200,$data, 'success');
    }

    public function getRedis()
    {
        $live1_arr = [];
        // 直接错误  『 Redis server 127.0.0.1:6379 went away 』
        // $redis = Redis::getInstance()->get("lovelive");
        // $redis = Di::getInstance()->get("REDIS");// ->get("lovelive")
        // for ($i = 0; $i <= 10000; $i++) {
        //     $redis->set('lovelive-' . $i,'honoka-' . $i);
        //     $live1 = $redis->get('lovelive');
        //     $live1_arr[] = $live1;
        // }
        
        $chan = new \Swoole\Coroutine\Channel(200);
        $redis = go(function () use ($chan, $live1_arr) {
            $redis1=\EasySwoole\Pool\Manager::getInstance()->get('REDIS_POOL')->getObj();
            // $redis2=\EasySwoole\Pool\Manager::getInstance()->get('redis1')->getObj();
            // for ($i = 0; $i <= 200; $i++) {
            //     $redis1->set('lovelive-' . $i,'honoka-' . $i);
            //     $live1 = $redis1->get('lovelive');
            //     $live1_arr[] = $live1;
            // }
            $live1 = $redis1->get('lovelive');
            // $chan->push($live1_arr);
        
            // $redis2->set('name','仙士可2号');
            // var_dump($redis2->get('name'));
            
            //回收对象
            \EasySwoole\Pool\Manager::getInstance()->get('REDIS_POOL')->recycleObj($redis1);
            // \EasySwoole\Pool\Manager::getInstance()->get('redis2')->recycleObj($redis2);
        });
        // $live1_arr = $chan->pop();

        // 和go方法一样速度差不多
        // $redis1=\EasySwoole\Pool\Manager::getInstance()->get('REDIS_POOL')->getObj();
        // for ($i = 0; $i <= 5000; $i++) {
        //     $redis1->set('lovelive-' . $i,'honoka-' . $i);
        //     $live1 = $redis1->get('lovelive');
        //     $live1_arr[] = $live1;
        // }
        // \EasySwoole\Pool\Manager::getInstance()->get('REDIS_POOL')->recycleObj($redis1);

        return $this->writeJson(200,['redis_key' => $redis], 'success');
    }

    

}
