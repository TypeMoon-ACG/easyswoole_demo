<?php

namespace App\HttpController\Api;

use App\HttpController\Based;
use App\Lib\ClassArr;
use App\Lib\Upload\Image;
use App\Lib\Upload\Video;

class Upload extends Based
{

    public function file()
    {
        $request = $this->request();
        $files = $request->getSwooleRequest()->files;
        $types = array_keys($files);
        $type = $types[0];
        if (empty($type)) {
            return $this->writeJson(400, [], "请上传文件!!!");
        }

        try {
            // $file = new Video($request);
            // $file = new Image($request);
            $classObj = new ClassArr();
            $classStats = $classObj->uploadClass();
            $uploadObj = $classObj->initClass($type, $classStats, [$request, $type]);
            $bool = $uploadObj->upload();
        } catch (\Exception $e) {
            return $this->writeJson(400, [], "上传失败!" . $e->getMessage());
        }
        if (empty($bool)) {
            return $this->writeJson(400, [], "上传失败!!!");
        }
        return $this->writeJson(200, $bool, "success");
        // $video = $request->getUploadedFile("file");
        // $bool = $video->moveTo(EASYSWOOLE_ROOT . "/webroot/public/a.mp4");
        // $data = [
        //     "url" => EASYSWOOLE_ROOT . "/webroot/public/a.mp4",
        //     "size" => $video->getSize(),
        // ];

        // if ($bool) {
        //     return $this->writeJson(200, $data, 'success');
        // }
        // return $this->writeJson(401, $data, 'fail');
    }




}
