<?php

namespace App\Lib;

class ClassArr
{

    public function uploadClass(){
        return [
            "image" => "\App\Lib\Upload\Image",
            "video" => "\App\Lib\Upload\Video",
            "txt" => "App\Lib\Upload\Txt",
        ];
    }

    public function initClass($type, $supportedClass, $params = [], bool $needInstance = true)
    {
        // PHP 反射机制
        if (!array_key_exists($type, $supportedClass)) return false;
        $className = $supportedClass[$type];
        return $needInstance ? (new \ReflectionClass($className))->newInstanceArgs($params) : $className;
    }

}
