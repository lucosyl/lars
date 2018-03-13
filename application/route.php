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
use think\Route;

// 注册路由到index模块的index控制器
Route::rule('/','index/index/index');
Route::rule('jsapi','index/jsapi');
Route::rule('micropay','index/micropay');
Route::rule('native','index/native');
Route::rule('refund','index/refund');
Route::rule('orderquery','index/orderquery');
Route::rule('refundquery','index/refundquery');
Route::rule('download','index/download');
Route::rule('notify','index/notify');
Route::get('qrcode','index/qrcode');
// 注册路由到index模块的index控制器
Route::rule('alipay','alipay/index');
Route::rule('ali_pay','alipay/pagepay');
Route::rule('ali_query','alipay/query');
Route::rule('ali_refund','alipay/refund');
Route::rule('ali_refundquery','alipay/refundquery');
Route::rule('ali_close','alipay/close');
Route::rule('ali_notify','alipay/notify_url');
Route::rule('ali_return','alipay/return_url');



return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
