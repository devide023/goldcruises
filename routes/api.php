<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::any('test','Api\TestController@test');
Route::post('/login','Api\UserController@login');
Route::any('/user/list','Api\UserController@list');
Route::post('/user/add','Api\UserController@add');
