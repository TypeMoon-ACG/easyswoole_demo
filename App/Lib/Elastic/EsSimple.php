<?php

namespace App\Lib\Elastic;

class EsSimple extends EsBase
{

    public $index = "video_list";

    public $match_key = "name";

    public $match_type = "match";


}
