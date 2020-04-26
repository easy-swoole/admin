<?php


namespace App\Utility;


use App\Model\Admin\User;
use EasySwoole\EasySwoole\Config;
use EasySwoole\ORM\DbManager;

class Installer
{
    const INSTALL_FILE = EASYSWOOLE_ROOT.'/install.lock';
    const RELEASE_TABLE_MODEL = [
        User::class,
    ];

    public static function isInstall():bool
    {
        $is = Config::getInstance()->getConf('INSTALL');
        if(!$is){
            if(file_exists(self::INSTALL_FILE)){
                $is = true;
                Config::getInstance()->setConf('INSTALL',true);
            }
        }
        return $is;
    }

    public static function setInstall():bool
    {
        file_put_contents(self::INSTALL_FILE,date('yy-m-d h:i:s'));
        Config::getInstance()->setConf('INSTALL',true);
        return true;
    }

    public static function install()
    {

    }
}