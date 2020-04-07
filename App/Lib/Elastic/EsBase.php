<?php

namespace App\Lib\Elastic;

use EasySwoole\Component\Di;

class EsBase
{

    public $client = null;

    public function __construct()
    {
        $this->client = Di::getInstance()->get('ES');
    }

    public function search($name, $from = 0, $size = 12)
    {
        if (empty($name)) return [];
        $params = [
            "index" => $this->index,
            // "type" => "_doc",
            // "id" => "1",
            "body" => [
                "query" => [
                    $this->match_type => [$this->match_key => $name]
                ],
                "from" => $from,
                "size" => $size,
            ]
        ];
        return $this->client->search($params);
    }

}
