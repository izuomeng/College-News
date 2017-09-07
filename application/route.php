<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
Route::rule('history','index/Index/history');
Route::rule('list','detail/Detail/list');
Route::rule('choose','detail/Detail/choose');
Route::rule('all','detail/Detail/all');
Route::rule('techDetail','detail/Detail/techDetail');
Route::rule('bdDetail','detail/Detail/bdDetail');
Route::rule('search','index/Index/search');
Route::rule('pcSearch','index/Pc/pcSearch');

Route::rule('pcIndex','index/Pc/pcIndex');
Route::rule('pcDetail','index/Pc/pcDetail');
Route::rule('pcTechDetail','index/Pc/pcTechDetail');
Route::rule('pcBdDetail','index/Pc/pcBdDetail');
Route::rule('test','index/Pc/test');

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
