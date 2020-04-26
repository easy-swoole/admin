<?php


namespace App\Model\Admin;


use App\Model\BaseModel;
use EasySwoole\ORM\Utility\Schema\Table;

class AdminUser extends BaseModel
{
    protected $tableName = 'admin_user_list';

    function schemaInfo(bool $isCache = true): Table
    {
        $table = new Table($this->tableName);
        $table->colInt('adminId')->setIsAutoIncrement()->setIsPrimaryKey();
        $table->colVarChar('account',18)->setIsUnique()->setIsNotNull();
        $table->colVarChar('password',32);
        $table->colVarChar('session',32)->setIsUnique();
        $table->setIfNotExists();
        return $table;
    }
}