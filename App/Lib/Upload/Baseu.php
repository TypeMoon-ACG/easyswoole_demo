<?php

namespace App\Lib\Upload;

class Baseu
{

    public $requets = [];

    public $file = '';

    public $size = 0;

    public $type = '';
    
    public $clientMediaType = '';

    public $clientMediaExt = '';

    public function __construct($request, $type = '')
    {
        $this->requets = $request;
        $this->type = $type;
        // $files = $this->requets->getSwooleRequest()->files;
        // $types = array_keys($files);
        // $this->type = $types[0];
        var_dump($type);
    }

    public function upload()
    {
        if ($this->type != $this->fileType) return false;

        $file = $this->requets->getUploadedFile($this->type);
        $file_name = $file->getClientFileName();
        $this->size = ($file->getSize() / 1024) / 1024;
        $this->clientMediaType = $file->getClientMediaType();
        $this->clientMediaExt = pathinfo($file_name, PATHINFO_EXTENSION);
        $this->checkType();
        $this->checkSize();
        $dir = $this->getFile($file_name);
        $result = $file->moveTo($dir);
        if (!empty($result)) {
            return [
                "url" => $this->file,
                "size" => $this->size,
                "ext" => $this->clientMediaExt,
            ];
        }
        // var_dump($this->file, $dir);
        return false;
    }

    public function getFile($file_name)
    {
        $basedir = $this->type . "/" . date('Y') . '/' . date('m');
        $dir = EASYSWOOLE_ROOT . "/webroot/" . $basedir;
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        // 生成唯一ID
        $bytes = openssl_random_pseudo_bytes(12);
        $new_name = date("Ymd_His") . '_' . substr(bin2hex($bytes), 0, 12) . "." . $this->clientMediaExt;

        $this->file = '/' . $basedir . '/' . $new_name;
        return $dir . '/' . $new_name;
    }

    public function checkType()
    {
        if (!in_array($this->clientMediaExt, $this->fileExtTypes)) {
            throw new \Exception("上传{$this->type}文件不合法!");
        }
        return true;
    }

    public function checkSize()
    {
        if ($this->size > $this->maxSize) return false;
    }


}
