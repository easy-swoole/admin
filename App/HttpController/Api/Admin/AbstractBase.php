<?php


namespace App\HttpController\Api\Admin;


use App\HttpController\Api\ApiBase;
use App\Model\Admin\AccessModule;
use App\Model\Admin\User;
use EasySwoole\Http\Message\Status;
use EasySwoole\HttpAnnotation\Exception\Annotation\ParamValidateError;
use EasySwoole\Tracker\PointContext;

abstract class AbstractBase extends ApiBase
{
    protected $noneAuthAction = [];
    protected $who;
    protected $acl;

    const ADMIN_COOKIE_NAME = 'admin_session';

    abstract protected function moduleName():string ;

    protected function onRequest(?string $action): ?bool
    {
        $point = PointContext::getInstance()->current()->appendChild("AdminApi-{$this->moduleName()}@OnRequest");
        //控制器为pool模式，强制重置，
        $this->who = null;
        $this->acl = null;
        if(in_array($action,$this->noneAuthAction)){
            $point->end();
            return true;
        }
        if(!$this->who()){
            $this->writeJson(Status::CODE_UNAUTHORIZED,[
                'errorCode'=>-2
            ],'请重新登录');
            $point->end();
            return false;
        }
        $acl = $this->adminAcl();
        if(!isset($acl[$this->moduleName()][$action])){
            $this->writeJson(Status::CODE_UNAUTHORIZED,[
                'errorCode'=>-1
            ],'权限不足');
            $point->end();
            return false;
        }
        $point->end();
        $point->next("AdminApi-{$this->moduleName()}@$action");
        return true;
    }

    protected function afterAction(?string $actionName): void
    {
        $p = PointContext::getInstance()->find("AdminApi-{$this->moduleName()}@$actionName");
        if($p){
            $p->end();
        }
        parent::afterAction($actionName);

    }

    protected function onException(\Throwable $throwable): void
    {
        if ($throwable instanceof ParamValidateError) {
            $this->error($throwable->getValidate()->getError()->__toString());
        } else {
            $this->error($throwable->getMessage());
        }
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