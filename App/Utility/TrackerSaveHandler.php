<?php


namespace App\Utility;


use EasySwoole\Tracker\Point;
use EasySwoole\Tracker\SaveHandlerInterface;

class TrackerSaveHandler implements SaveHandlerInterface
{
    function save(?Point $point, ?array $globalArg = []): bool
    {
        Point::toString($point);
        return  true;
    }
}