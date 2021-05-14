<?php
/**
 * Created by
 * User BaiXiantao
 * Date 2020/12/6
 * Time 9:06
 */
use think\facade\Route;

Route::get('admin', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('login','login/login');