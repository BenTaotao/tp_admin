<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('admin', function () {
    return 'hello,ThinkPHP6!';
});
//跨域
Route::get('app', 'app/*')
    ->ext('html')
    ->allowCrossDomain([
        'Access-Control-Allow-Origin'        => 'thinkphp.cn',
        'Access-Control-Allow-Credentials'   => 'true',
        'Access-Control-Max-Age'             => 600,
    ]);

Route::get('/', function () {
    return 'Hello,world!';
});
Route::rule('/', 'index');
//Route::get('hello/:name', 'index/hello');
Route::get('index', 'index/index');
//Route::get('admin', 'admin/index');

//设置路由--第三方登录的路由设置
//Route::get('oauth/callback','index/oauth/callback');
