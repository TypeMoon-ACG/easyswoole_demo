<?php

namespace App\HttpController\Api;

use App\HttpController\Based;
use App\Lib\Elastic\EsSimple;
use EasySwoole\Component\Di;
use Elasticsearch\ClientBuilder;

class Elastic extends Based
{

    public function testIndex()
    {
        $params = [
            "index" => "video_list",
            // "type" => "_doc",
            // "id" => "1",
            "body" => [
                "query" => [
                    "match" => ["name" => "雪步"]
                ],
                
            ]
        ];
        // $client = ClientBuilder::create()->setHosts(["127.0.1.1:6001"])->build();
        // $client = Di::getInstance()->get('ES');
        // $res = $client->get($params);
        // $res = $client->search($params);
        $client = new EsSimple();
        $client->match_key = "content";
        $res = $client->search("爱生活！", 1, 2);
        return $this->writeJson(200, $res, 'success');
    }

    public function index()
    {
        if (!$this->params['keyword']) return $this->writeJson(200, $this->getPagingData([], 0));
        $keyword = $this->params['keyword'];

        $data = (new EsSimple())->search($keyword, $this->params['offset_page'], $this->params['limit']);
        // $new_data = array_column($data['hits']['hits'], null);
        foreach ($data['hits']['hits'] as $val) {
            $new_data[] = $val['_source'];
        }
        $data_after = $this->getPagingData($new_data, $data['hits']['total']['value']);
        $this->writeJson(200, $data_after, "success");
    }


}
