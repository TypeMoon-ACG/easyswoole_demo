<?php

namespace App\HttpController;

use EasySwoole\EasySwoole\Config;
use EasySwoole\Http\AbstractInterface\Controller;

class Based extends Controller
{

    public $params = [];


    public function onRequest($action): ?bool
    {
        // $this->writeJson(404, [], 'fail');
        $this->getParams();
        return true;
    }

    public function getParams()
    {
        $page = Config::getInstance()->getConf('PAGE');
        $params = $this->request()->getRequestParam();
        $params['page'] = !empty($params['page']) && $params['page'] < $page['MAX_PAGE'] ? intval($params['page']) : 1;
        $params['limit'] = !empty($params['limit']) && $params['limit'] < $page['MAX_LIMIT'] ? intval($params['limit']) : 12;
        $params['offset_page'] = ($params['page'] - 1) * $params['limit'];

        $this->params = $params;
    }

    public function getPagingData($data, $count)
    {
        $all_page = ceil($count / $this->params['limit']);
        $max_page = Config::getInstance()->getConf('PAGE.MAX_PAGE');
        if ($all_page > $max_page) $all_page = $max_page;
        
        $data = $data ?? [];
        if (count($data) > 1) {
            $data = array_splice($data, $this->params['offset_page'], $this->params['limit']);
        }
        return [
            "all_page" => $all_page,
            "now_page" => $this->params['page'],
            "count" => $count,
            "list" => $data,
        ];
    }


    // public function onException(\Throwable $throwable): void
    // {
    //     $this->writeJson(501, [], "Page Is Not Found!");
    // }
    
    public function index()
    {
        // 子类继承抽象的父类, 父类有一个抽象的方法子类必须实现这个方法
        return '';
    }

    public function newOnlyKeys($array, $keys)
    {
        return array_intersect_key($array, array_flip($keys));
    }


}
