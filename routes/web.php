<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Api\WeChatController@checkSignature');
Route::get('/test','Web\MyTestController@html_text');
Route::get('/info','Web\MyTestController@info');
Route::get('/redis','Web\MyTestController@redistest');
Route::get('/films',[\App\Http\Controllers\Web\MyTestController::class,'filminfo']);
Route::get('/hotelbook',[\App\Http\Controllers\Web\MyTestController::class,'hotelbook']);
Route::get('/gethotelbook',[\App\Http\Controllers\Web\MyTestController::class,'gethotelbook']);
Route::get('/redistest',[\App\Http\Controllers\Redis\RedisController::class,'userinfo']);
Route::get('/redisuser',[\App\Http\Controllers\Web\MyTestController::class,'finduser']);
Route::get('/redismsg',[\App\Http\Controllers\Web\MyTestController::class,'msg']);
Route::get('/gold_order','Web\ES\ESController@getorders');
Route::get('/localuser','Web\ES\ESController@getlocalusers');
