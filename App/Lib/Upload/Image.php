<?php

namespace App\Lib\Upload;

class Image extends Baseu
{

    public $fileType = "image";

    public $maxSize = 10;

    public $fileExtTypes = [
        "png", "jpeg", "jpg"
    ];

    public function checkType()
    {
        // 重写方法
        if (!in_array($this->clientMediaExt, $this->fileExtTypes)) {
            throw new \Exception("上传{$this->type}文件不合法@#@#@#!");
        }
        return true;
    }

}
