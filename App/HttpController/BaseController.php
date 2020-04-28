<?php


namespace App\HttpController;


use EasySwoole\Http\Message\Status;
use EasySwoole\HttpAnnotation\AnnotationController;
use EasySwoole\Validate\Validate;

class BaseController extends AnnotationController
{
    public function paramsValidate(Validate $validate)
    {
        $params = $this->request()->getRequestParam();
        $bool = $validate->validate($params);
        if (!$bool) {
            $this->writeJson(Status::CODE_BAD_REQUEST, null, $validate->getError()->__toString());
            return false;
        }
        return $validate->getVerifiedData();
    }

    
}