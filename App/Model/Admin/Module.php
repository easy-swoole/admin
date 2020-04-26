<?php


namespace App\Model\Admin;


use App\Model\BaseModel;
use EasySwoole\ORM\Utility\Schema\Table;

/**
 * Class AdminModule
 * @package App\Model\Admin
 * 管理后台模块
 */
class Module extends BaseModel
{
    protected $tableName = 'admin_module';

    function schemaInfo(bool $isCache = true): Table
    {
        $table = new Table($this->tableName);
        $table->colVarChar('module',45)->setIsUnique()->setIsNotNull()->setIsPrimaryKey();
        $table->colVarChar('name',45)->setDefaultValue('模块名称');
        $table->colVarChar('note',255)->setDefaultValue('模块备注');
        $table->setIfNotExists();
        return $table;
    }
}