<?php
declare (strict_types = 1);

namespace app\http\controller;

use think\worker\Server;

class Worker extends Server
{
    protected $socket = 'http://0.0.0.0:2346';

    public function onMessage($connection,$data)
    {
        $connection->send(json($data));
    }

    public function index()
    {
        return '您好！这是一个[http]示例应用';
    }
}
