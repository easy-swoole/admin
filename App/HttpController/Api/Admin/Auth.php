<?php


namespace App\HttpController\Api\Admin;


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

    }

    public function logout()
    {

    }
}