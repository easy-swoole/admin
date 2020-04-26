<?php


namespace App\HttpController;



use Swoole\Coroutine\Channel;
use Swoole\Coroutine;

class Index extends BaseController
{
    function test()
    {
        $channel = new Channel(8);
        Coroutine::create(function ()use($channel){
            Coroutine::sleep(2);
            $channel->push(1);
        });

        $this->writeJson(200,$channel->pop());
    }
}