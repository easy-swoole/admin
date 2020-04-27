<?php


namespace App\HttpController\Api\Admin;

use EasySwoole\Http\Message\Status;

class Auth extends AbstractBase
{
    protected $noneAuthAction = [
        'login'
    ];

    protected function moduleName(): string
    {
        return 'auth';
    }

    public function login()
    {
        $request = $this->request();
        $data = $request->getRequestParam();
        // 没有输入账号则报错
        if (!$data['account']) {
            $this->writeJson(Status::CODE_BAD_REQUEST, [
                'errorCode' => -2
            ], '没输入账号');
            return false;
        }
        // 没有输入密码则报错
        if (!$data['password']) {
            $this->writeJson(Status::CODE_BAD_REQUEST, [
                'errorCode' => -1
            ], '没输入密码');
            return false;
        }

        $admin_model = new \App\Model\Admin\User();
        // 如果密码账号没错则设置登录状态
        $admin_id = $admin_model->check_password($data['account'],$data['password']);

        if ($admin_id) {
            $hashSession = md5(microtime(true).$admin_id);
            $admin_model->update(['session'=>$hashSession],['adminId'=>$admin_id]);
            $this->response()->setCookie('adminSession', $hashSession, time() + 3600, '/');
            $admin_info = ["admin_id"=>$admin_id,"admin_account"=>$data['account']];
            $this->writeJson(Status::CODE_OK, $admin_info,"login successed!");
        }else{
            $this->writeJson(Status::CODE_BAD_REQUEST, [
                'errorCode' => -1
            ], '账号错误或者密码错误');
            return false;
        }
    }

    public function logout()
    {

    }
}