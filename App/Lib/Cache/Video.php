<?php

namespace App\Lib\Cache;

use App\Model\Video as VideoModel;
use EasySwoole\FastCache\Cache;
use Exception;

class Video
{

    public function setIndexVideo($cache_type)
    {
        $cate_list = array_keys((new VideoModel)->cate_list);
        foreach ($cate_list as $cate_id) {
            $where = [];
            if (!empty($cate_id)) $where = ['cat_id' => $cate_id];
            $video = VideoModel::allList($where);
            
            switch ($cache_type) {
                case 'file':
                    // 文件方式
                    $res = file_put_contents(VideoModel::json_path() . 'video_' . $cate_id . '.json', json_encode($video, 256 + 64));
                    break;
                
                case 'table':
                    // 组件-内存方式
                    $res = Cache::getInstance()->set(VideoModel::$video_cat_id_key . $cate_id, $video);
                    break;

                default:
                    throw new \Exception("参数方式不正确，不支持当前参数!");
                    break;
            }
            if ($res) {
                echo "视频列表写入成功| 字节树：" . $res . PHP_EOL;
            } else {
                // 写入失败，记得报警！
                echo "视频列表写入失败！" . PHP_EOL;
            }
        }
        echo '-----------------------------------------------------' . PHP_EOL;
    }

    public function getIndexVideo(int $cate_id = 0, $cache_type = '')
    {
        // 后期可以写入配置里
        if ($cache_type == '') $cache_type = "table";
        $res = [];
        
        switch ($cache_type) {
            case 'file':
                // 文件方式
                if (!file_exists(VideoModel::json_path() . 'video_' . $cate_id . '.json')) throw new \Exception("缓存文件不存在!");
                $res = json_decode(file_get_contents(VideoModel::json_path() . 'video_' . $cate_id . '.json'), true);
                break;
            
            case 'table':
                // 组件-内存方式
                $res = Cache::getInstance()->get(VideoModel::$video_cat_id_key . $cate_id);
                break;

            default:
                throw new \Exception("参数方式不正确，不支持当前参数!");
                break;
        }
        return $res;
    }

}
