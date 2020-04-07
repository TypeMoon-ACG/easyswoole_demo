<?php

namespace App\Lib\Elastic;

use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Config;
use Elasticsearch\ClientBuilder;

class Elastic
{

    use Singleton;

    public $client = null;

    private function __construct()
    {
        $config = Config::getInstance()->getConf('ELASTIC');
        $hosts = ["{$config['host']}:{$config['port']}"];
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();

        if (empty($this->client)) {
            throw new \Exception("ESClient Not Found!!");
        }
    }

    public function __call($name, $args)
    {
        
        return $this->client->$name(...$args);
    }

}
