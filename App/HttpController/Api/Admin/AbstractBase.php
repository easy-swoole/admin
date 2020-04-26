<?php


namespace App\HttpController\Api\Admin;


use App\HttpController\Api\ApiBase;
use App\Model\Admin\AccessModule;
use App\Model\Admin\User;
use EasySwoole\Http\Message\Status;

abstract class AbstractBase extends ApiBase
{
    protected $noneAuthAction = [];
    protected $who;
    protected $acl;

    const ADMIN_COOKIE_NAME = 'admin_session';

    abstract protected function moduleName():string ;

    protected function onRequest(?string $action): ?bool
    {
        //控制器为pool模式，强制重置，
        $this->who = null;
        $this->acl = null;
        if(in_array($action,$this->noneAuthAction)){
            return true;
        }
        if(!$this->who()){
            $this->writeJson(Status::CODE_UNAUTHORIZED,[
                'errorCode'=>-2
            ],'请重新登录');
            return false;
        }
        $acl = $this->adminAcl();
        if(!isset($acl[$this->moduleName()][$action])){
            $this->writeJson(Status::CODE_UNAUTHORIZED,[
                'errorCode'=>-1
            ],'权限不足');
            return false;
        }
        return true;
    }


    protected function who():?User
    {
        if(!$this->who){
            $cookie = $this->request()->getCookieParams(static::ADMIN_COOKIE_NAME);
            if(empty($cookie)){
                $cookie = $this->request()->getRequestParam(static::ADMIN_COOKIE_NAME);
            }
            if($cookie){
                $this->who = User::create()->where(['session'=>$cookie])->get();
            }
        }
        return $this->who;
    }


    protected function adminAcl():?array
    {
        //这边需要关联Module表
        $ret = AccessModule::create()->where('adminId',$this->who()->adminId)->all();
        return $this->acl;
    }
}