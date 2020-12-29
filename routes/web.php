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
Route::get('/gold_order','Web\ES\ESController@getorders');
Route::get('/localuser','Web\ES\ESController@getlocalusers');
