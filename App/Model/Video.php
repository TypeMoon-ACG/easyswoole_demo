<?php

namespace App\Model;

use EasySwoole\ORM\Utility\Schema\Table;

class Video extends Basem
{

    public static $video_cat_id_key = "fastcache_video_all_list_cat_id";



    public static function json_path()
    {
        $path = EASYSWOOLE_ROOT . '/webroot/video/json_video/';
        if (!file_exists($path)) mkdir($path, 0755, true);
        return $path;
    }

    public function getCatIdAttr($value, $data)
    {
        $cate_list = [
            1 => "ACG-周边",
            2 => "Game-游戏",
            3 => "Animate-动漫",
            4 => "Comic-漫画",
            5 => "Live-演唱会",
        ];
        return $cate_list[$value];
    }

    public function getStatusAttr($value, $data)
    {
        $cate_list = [
            1 => "通过",
            2 => "待审核",
            3 => "拒绝",
            
        ];
        return $cate_list[$value];
    }

    public function schemaInfo(bool $isCache = true): Table
    {
        $table = new Table("video");
        $table->colInt("id")->setIsPrimaryKey()->setIsAutoIncrement();
        $table->colChar("name", 32)->setDefaultValue("");
        $table->colInt("cat_id")->setIsUnsigned()->setDefaultValue(0);
        $table->colChar("url", 64)->setDefaultValue("");
        $table->colTinyInt("type")->setDefaultValue(9)->setIsUnsigned();
        $table->colChar("content", 2800)->setDefaultValue("");
        $table->colInt("user_id")->setIsUnsigned()->setDefaultValue(0);
        $table->colChar("uploader")->setDefaultValue("");
        $table->colTinyInt("status")->setDefaultValue(9)->setIsUnsigned();
        $table->colInt("update_time")->setIsUnsigned();
        $table->colTimestamp("create_time")->setDefaultValue("CURRENT_TIMESTAMP");
        return $table;
    }

    public function list($page, $limit)
    {
        // select found_rows(); 获取上一个select语句查询到的行数
        // row_count 获取上一个update, insert, delete 影响的行数
        $rows = $this->field("id,name,cat_id,url,user_id,uploader")->limit($limit * ($page - 1), $limit)->withTotalCount()->order("create_time", "desc");
        return [
            'list' => $rows->all(),
            'count' => $rows->lastQueryResult()->getTotalCount()
        ];
    }

    public static function listAll($page, $limit)
    {
        // 静态类
        $rows = self::create()->field("id,name,cat_id,url,user_id,uploader")->limit($limit * ($page - 1), $limit)->withTotalCount()->order("create_time", "desc");
        return [
            'list' => $rows->all(),
            'count' => $rows->lastQueryResult()->getTotalCount()
        ];
    }

    public static function allList($where = [], $limit = 1000)
    {
        $rows = self::create()->where($where)->limit($limit)->order("create_time", "desc")->all();
        return $rows;
    }


    public function add(array $data)
    {
        $user_info = $this->user_list[5];
        $data['user_id'] = $user_info['id'];
        $data['uploader'] = $user_info['name'];
        return $this->create()->data($data)->save();
    }

}
