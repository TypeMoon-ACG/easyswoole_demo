<?php

namespace App\Model;

use EasySwoole\ORM\AbstractModel;
use EasySwoole\Http\Message\Message;

class Basem extends AbstractModel
{

    public $user_list = [
        ['id' => 1, 'name' => 'honoka', 'mobile' => '15123232101', 'age' => 15, 'create_time' => 1584195405,],
        ['id' => 2, 'name' => 'kotori', 'mobile' => '15123232102', 'age' => 15, 'create_time' => 1584195305,],
        ['id' => 3, 'name' => 'umi', 'mobile' => '15123232103', 'age' => 15, 'create_time' => 1584194306,],
        ['id' => 4, 'name' => 'rin', 'mobile' => '15123232104', 'age' => 16, 'create_time' => 1584194305,],
        ['id' => 5, 'name' => 'hanayo', 'mobile' => '15123232105', 'age' => 16, 'create_time' => 1584195416,],
        ['id' => 6, 'name' => 'maki', 'mobile' => '15123232106', 'age' => 16, 'create_time' => 1584174305,],
        ['id' => 7, 'name' => 'eli', 'mobile' => '15123232107', 'age' => 16, 'create_time' => 1584164316,],
        ['id' => 8, 'name' => 'nozomi', 'mobile' => '15123232108', 'age' => 16, 'create_time' => 1584194316,],
        ['id' => 9, 'name' => 'nico', 'mobile' => '15123232109', 'age' => 16, 'create_time' => 1584185293,],
        ['id' => 10, 'name' => 'satsuki', 'mobile' => '15123232110', 'age' => 17, 'create_time' => 1584195315,],
        ['id' => 11, 'name' => 'akiha', 'mobile' => '15123232111', 'age' => 17, 'create_time' => 1584194406,],
        ['id' => 12, 'name' => 'chihaya', 'mobile' => '15123232112', 'age' => 17, 'create_time' => 1584197515,],
        ['id' => 13, 'name' => 'yokiho', 'mobile' => '15123232113', 'age' => 17, 'create_time' => 1584174306,],
        ['id' => 14, 'name' => 'haruka', 'mobile' => '15123232114', 'age' => 12, 'create_time' => 1584164417,],
        ['id' => 15, 'name' => 'hisui', 'mobile' => '15123232115', 'age' => 12, 'create_time' => 1584074317,],
        ['id' => 16, 'name' => 'kohaku', 'mobile' => '15123232116', 'age' => 12, 'create_time' => 1584195517,],
    ];

    public $cate_list = [
        1 => "ACG-周边",
        2 => "Game-游戏",
        3 => "Animate-动漫",
        4 => "Comic-漫画",
        5 => "Live-演唱会",
    ];
    
}
