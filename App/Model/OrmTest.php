<?php

namespace App\Model;

use EasySwoole\ORM\AbstractModel;
use EasySwoole\ORM\Utility\Schema\Table;

class OrmTest extends AbstractModel
{

    public function schemaInfo(bool $isCache = true): Table
    {
        $table = new Table("orm_test");
        $table->colInt("id")->setIsPrimaryKey()->setIsAutoIncrement();
        $table->colChar("name", 16)->setDefaultValue("");
        $table->colTinyInt("age")->setDefaultValue(9)->setIsUnsigned();
        $table->colChar("mobile")->setIsUnique();
        $table->colInt("updatetime")->setIsUnsigned();
        $table->colTimestamp("createtime")->setDefaultValue("CURRENT_TIMESTAMP");
        return $table;
    }


}
