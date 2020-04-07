<?php

namespace App\Lib\Upload;

class Video extends Baseu
{

    public $fileType = "video";

    public $maxSize = 60;

    public $fileExtTypes = [
        'mp4', 'x-flv', 'mkv'
    ];

}
