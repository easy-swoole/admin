<?php


namespace App\HttpController\Api\Admin;


use App\HttpController\Api\ApiBase;

abstract class AbstractAdminBase extends ApiBase
{
    protected $noneAuthAction = [];

    abstract protected function moduleName():string ;

    protected function onRequest(?string $action): ?bool
    {
        if(in_array($action,$this->noneAuthAction)){
            return true;
        }
        return true;
    }
}