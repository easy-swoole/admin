<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace App\Validate\Admin;


use App\Validate\BaseValidate;

class UserValidate extends BaseValidate
{
    public function __construct()
    {
        $this->addColumn('account','帐号')->required()->lengthMax(18);
        $this->addColumn('password','密码')->required();
    }
}