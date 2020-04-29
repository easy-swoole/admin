<?php


namespace App\HttpController;


use EasySwoole\Http\Message\Status;
use EasySwoole\HttpAnnotation\AnnotationController;

class BaseController extends AnnotationController
{

    public function success($result = [], $msg = 'OK')
    {
        $this->writeJson(Status::CODE_OK, $result, $msg);
        return true;
    }

    public function error($msg = 'Fail', $code = '-1', $statusCode = Status::CODE_OK)
    {
        $this->writeJson($statusCode, [
            'errCode' => $code
        ], $msg);
        return false;
    }
}