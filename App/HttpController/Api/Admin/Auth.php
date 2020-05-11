<?php


namespace App\HttpController\Api\Admin;

use App\Model\Admin\User;
use EasySwoole\HttpAnnotation\AnnotationTag\Method;
use EasySwoole\HttpAnnotation\AnnotationTag\Param;

class Auth extends AbstractBase
{
    protected $noneAuthAction = [
        'register',
        'login'
    ];

    protected function moduleName(): string
    {
        return 'auth';
    }


    /**
     * @Method(allow={POST})
     * @Param(name="account",alias="帐号",from={POST},required="",notEmpty="",lengthMax="18")
     * @Param(name="password",alias="密码",from={POST},required="",notEmpty="",lengthMin="8")
     */
    public function register($account, $password)
    {
        $userModel = new User();

        $userInfo = $userModel->get(['account' => $account]);
        if ($userInfo) return $this->error('帐号已存在');

        $userModel->account = $account;
        $userModel->password = md5($password);
        if (!$userModel->save()) return $this->error('帐号注册失败');

        return $this->success([], '帐号注册成功');
    }

    /**
     * @Method(allow={POST})
     * @Param(name="account",alias="帐号",from={POST},required="",notEmpty="")
     * @Param(name="password",alias="密码",from={POST},required="",notEmpty="")
     */
    public function login($account, $password)
    {
        $userModel = new User();

        $adminId = $userModel->checkPassword($account, $password);
        if (!$adminId) return $this->error('帐号或密码错误');

        $session = md5(microtime(true) . $adminId);
        $userModel->update(['session' => $session], ['adminId' => $adminId]);

        $this->response()->setCookie('adminSession', $session, time() + 3600, '/');
        $adminInfo = ["admin_id" => $adminId, "admin_account" => $account];
        return $this->success($adminInfo);
    }

    public function logout()
    {

    }
}