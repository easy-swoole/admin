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
        $table->colVarChar('moduleId',10)->setIsAutoIncrement()->setIsPrimaryKey();
        $table->colVarChar('moduleName',45)->setColumnComment('模块名称');
        $table->colVarChar('moduleAction',45)->setColumnComment('模块名称');
        $table->colVarChar('note',255)->setColumnComment('模块备注');
        $table->colVarChar('moduleHash',45)->setIsNotNull()->setIsUnique();
        $table->setIfNotExists();
        return $table;
    }
}