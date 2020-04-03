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
Route::post('/user/edit','Api\UserController@edit');
Route::post('/user/chpwd','Api\UserController@modifypwd');

Route::post('/role/list','Api\RoleController@list');
Route::post('/role/add','Api\RoleController@add');
Route::post('/role/edit','Api\RoleController@edit');

Route::post('/menu/list','Api\MenuController@list');
Route::post('/menu/add','Api\MenuController@add');
Route::post('/menu/edit','Api\MenuController@edit');

Route::post('/organize/list','Api\OrganizeController@list');
Route::post('/organize/add','Api\OrganizeController@add');
Route::post('/organize/edit','Api\OrganizeController@edit');
