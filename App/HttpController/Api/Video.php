<?php

namespace App\HttpController\Api;

use App\HttpController\Based;
use App\Model\Video as VideoModel;
use App\Lib\Cache\Video as VideoCache;
use App\Lib\Pool\MysqlPool;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\FastCache\Cache;
use EasySwoole\Pool\Manager;

class Video extends Based
{

    public function info()
    {
        $params = $this->request()->getRequestParam();
        if (empty($params['id'])) return $this->writeJson(400, [], "请求失败：缺少参数！");
        $res = VideoModel::create()->get($params['id']);

        
        TaskManager::getInstance()->async(function () use ($params) {
            // 在非异步任务中使用Di容器可能造成返回值为false, 在异步中也会有false！可能是没有及时返回但数据正常递增
            $bool = Di::getInstance()->get('REDIS')->zincrby('video_play_info', 1, $params['id']);
            var_dump($bool);
        }, function () {
            var_dump('任务完成！！！');
        });
        return $this->writeJson(200, $res, "success");
    }

    public function list()
    {
        $params = $this->request()->getRequestParam();
        $params['cat_id'] = (int)$params['cat_id'] ?? 1;
        /*
        $json_file = VideoModel::json_path() . 'video_' . intval($params['cat_id']) . '.json';
        if (!file_exists($json_file)) return $this->writeJson(404, [], "分类不存在！");
        $video_list = json_decode(file_get_contents($json_file), true);

        $video_list = Cache::getInstance()->get(VideoModel::$video_cat_id_key . $params['cat_id']);
        */
        
        $video_list = (new VideoCache())->getIndexVideo($params['cat_id']);
        $count = count($video_list);
        // $data = array_splice($video_list, $this->params['offset_page'], $this->params['limit']);
        // $list = $this->getPagingData($data, $count);
        $list = $this->getPagingData($video_list, $count);
        return $this->writeJson(200, $list, "success");
    }

    public function add()
    {
        
        $params = $this->request()->getRequestParam();
        $data = $this->newOnlyKeys($params, ['name','cat_id','url','content','uploader']);
        $data['type'] = 1;
        $data['cat_id'] = 1;
        $data['status'] = 1;
        $data['update_time'] = time();
        $bool = (new VideoModel())->add($data);
        // $bool = (new VideoModel())->get(1)->toArray();
        return $this->writeJson(200, ['id' => $bool, 'data' => $params], "success");
    }

    public function videoList()
    {
        // 连接池方式 ab测试 ab -n 50000 -c 1000 http://easyswoole-a.moon/api/video/videoList

        // QPS 415  协程数量 MAX-200 MIN-32
        // $res = VideoModel::create()->all();
        // return $this->writeJson(200, ['asd' => 12323], "success");

        // QPS 433  协程数量 MAX-200 MIN-32
        $res = go(function () {
            VideoModel::create()->all();
        });
        return $this->writeJson(200, $res, "success");

        // QPS 3630
        // $res = go(function (){
        //     $mysql=\EasySwoole\Pool\Manager::getInstance()->get('MYSQL_POOL')->getObj();
        //     $mysql->queryBuilder()->get('video');
        //     $res = $mysql->execBuilder();
        //     //回收对象
        //     \EasySwoole\Pool\Manager::getInstance()->get('MYSQL_POOL')->recycleObj($mysql);
        // });
        // return $this->writeJson(200, $res, "success");

        // 直接Over
        // $config = new \EasySwoole\Mysqli\Config([
        //     'host'          => '127.0.0.1',
        //     'port'          => 22331,
        //     'user'          => 'printemps',
        //     'password'      => 'lovelive',
        //     'database'      => 'easyswoole',
        //     'timeout'       => 5,
        //     'charset'       => 'utf8mb4',
        // ]);
        // $client = new \EasySwoole\Mysqli\Client($config);
        // $client = Di::getInstance()->get('MYSQLI');
        // $res = $client->queryBuilder()->get('video');
        // return $this->writeJson(200, $client->execBuilder(), "success");
    }

}
