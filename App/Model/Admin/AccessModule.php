<?php


namespace App\Model\Admin;


use App\Model\BaseModel;
use EasySwoole\ORM\Utility\Schema\Table;

/**
 * Class AccessModule
 * @package App\Model\Admin
 * acl
 */
class AccessModule extends BaseModel
{
    protected $tableName = 'admin_access_module';

    function schemaInfo(bool $isCache = true): Table
    {
        $table = new Table($this->tableName);
        $table->colInt('accessId')->setIsUnique()->setIsAutoIncrement()->setIsPrimaryKey();
        $table->colInt('adminId')->setIsNotNull();
        $table->colInt('moduleId')->setIsNotNull();
        $table->colInt('accessHash')->setIsNotNull()->setIsUnique();
        $table->setIfNotExists();
        return $table;
    }
}