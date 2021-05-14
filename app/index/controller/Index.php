<?php
//declare (strict_types=1);

namespace app\index\controller;

use think\api\Client;
use think\cache\driver\Redis;
use think\facade\View;

class Index
{
    public function index()
    {

//        $client = new Client("0777d1f7032e232dd5fdef5976212f08");

//        $result = $client->telecomLocation()
//            ->withPhone('18829025239')
//            ->request();
//        $result = $client->literaryPoetry()
//            ->withWord('枫桥夜泊')
//            ->request();
//        $result = $client->unnBatchUcheck()
//            ->withMobiles('手机号码')
//            ->request();
//        $result = $client->idcardIndex()
//            ->withCardno('622822199303291117')
//            ->request();
//        dump($result);
//        dump($result);
//        return json($result);
//        $redis = new Redis();
//
//        $redis->set('name', '这是测试redis的文件');
//        $gets = $redis->get('name');

//        return json($gets);
        return json("此处为index应用");
    }
}
