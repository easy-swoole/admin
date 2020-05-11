<?php
namespace EasySwoole\EasySwoole;


use App\Utility\TrackerSaveHandler;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use EasySwoole\ORM\Db\Config as OrmConfig;
use EasySwoole\Tracker\Point;
use EasySwoole\Tracker\PointContext;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
        $config = new OrmConfig(Config::getInstance()->getConf('MYSQL'));
        DbManager::getInstance()->addConnection(new Connection($config));
        PointContext::getInstance()->setSaveHandler(new TrackerSaveHandler());
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        PointContext::getInstance()->setGlobalArg([
            'path'=>$request->getUri()->__toString(),
            'startTime'=>round(microtime(true),4)
        ]);
        PointContext::getInstance()->createStart('onRequest');
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        PointContext::getInstance()->save();
    }
}