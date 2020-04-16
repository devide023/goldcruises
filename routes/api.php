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

Route::middleware('auth:api')->group(function (){

    Route::post('/user/list','Api\UserController@list');
    Route::post('/user/add','Api\UserController@add');
    Route::post('/user/del','Api\UserController@del');
    Route::post('/user/userrole','Api\UserController@userroles');
    Route::post('/user/userorg','Api\UserController@userorgs');
    Route::get('/user/menus','Api\UserController@getusermenus');
    Route::post('/user/edit','Api\UserController@edit');
    Route::post('/user/chpwd','Api\UserController@modifypwd');
    Route::get('user/info','Api\UserController@info');
    Route::post('/user/headimg','Api\UserController@upload_headimg');
    Route::get('/user/find','Api\UserController@findbyid');
    Route::any('/user/logout','Api\UserController@logout');

    Route::post('/role/list','Api\RoleController@list');
    Route::post('/role/add','Api\RoleController@add');
    Route::post('/role/edit','Api\RoleController@edit');
    Route::post('/role/del','Api\RoleController@del');
    Route::post('/role/roleuser','Api\RoleController@roleuser');
    Route::post('/role/rolemenu','Api\RoleController@rolemenu');
    Route::get('/role/users','Api\RoleController@getusers');
    Route::get('/role/menus','Api\RoleController@getmenus');

    Route::post('/menu/list','Api\MenuController@list');
    Route::post('/menu/add','Api\MenuController@add');
    Route::post('/menu/del','Api\MenuController@del');
    Route::post('/menu/edit','Api\MenuController@edit');
    Route::post('/menu/menurole','Api\MenuController@menuroles');
    Route::get('/menu/users','Api\MenuController@getusers');

    Route::post('/organize/list','Api\OrganizeController@list');
    Route::post('/organize/add','Api\OrganizeController@add');
    Route::post('/organize/del','Api\OrganizeController@del');
    Route::post('/organize/edit','Api\OrganizeController@edit');

    Route::get('/baseinfo/province','Api\BaseInfoController@province' );
    Route::get('/baseinfo/routes','Api\BaseInfoController@routelist' );

});

Route::post('/login','Api\UserController@login');

