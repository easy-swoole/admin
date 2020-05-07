<?php


namespace App\HttpController\Admin;


use App\HttpController\BaseController;

class Base extends BaseController
{
    protected function display(string $tpl,$args = null)
    {
        $file = EASYSWOOLE_ROOT.'/Static/Template/Admin/'.$tpl;
        $data = file_get_contents($file);
        $this->response()->write($data);
    }
}