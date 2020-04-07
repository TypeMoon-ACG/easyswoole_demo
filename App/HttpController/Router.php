<?php

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\AbstractRouter;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class Router extends AbstractRouter
{

    public function initialize(\FastRoute\RouteCollector $routeCollector)
    {
        $this->setGlobalMode(true);
        // $routeCollector->addRoute("GET", "/index/videoqq", function (Request $request, Response $response) {
        //     $response->write('Userid : asdasdad');
        //     return false;
        // });
        $routeCollector->addRoute("GET", "/index/videoqq", "/Api/Index/video");

        $this->setMethodNotAllowCallBack(function (Request $request,Response $response){
            $response->write('Action Not Found!!!');
            return false;//结束此次响应   --  未找到处理方法
        });
        $this->setRouterNotFoundCallBack(function (Request $request,Response $response){
            $response->write('Route Not Found!!!');
            return 'index';//重定向到index路由  --  未找到路由匹配
            return false;
        });

    }



}
