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
    Route::get('/user/del','Api\UserController@del');
    Route::post('/user/userrole','Api\UserController@userroles');
    Route::post('/user/userorg','Api\UserController@userorgs');
    Route::get('/user/menus','Api\UserController@getusermenus');
    Route::get('/user/routes','Api\UserController@userroutes');
    Route::post('/user/edit','Api\UserController@edit');
    Route::post('/user/chpwd','Api\UserController@modifypwd');
    Route::get('user/info','Api\UserController@info');
    Route::post('/user/headimg','Api\UserController@upload_headimg');
    Route::get('/user/find','Api\UserController@findbyid');
    Route::post('/user/disable','Api\UserController@disable');
    Route::post('/user/enable','Api\UserController@enable');
    Route::any('/user/logout','Api\UserController@logout');

    Route::post('/role/list','Api\RoleController@list');
    Route::post('/role/add','Api\RoleController@add');
    Route::post('/role/edit','Api\RoleController@edit');
    Route::get('/role/del','Api\RoleController@del');
    Route::post('/role/roleuser','Api\RoleController@roleuser');
    Route::post('/role/rolemenu','Api\RoleController@rolemenu');
    Route::get('/role/users','Api\RoleController@getusers');
    Route::get('/role/menus','Api\RoleController@getmenus');
    Route::get('/role/rolerel','Api\RoleController@getrolerel');
    Route::get('/role/rolemenupath','Api\RoleController@rolemenupath');

    Route::post('/menu/list','Api\MenuController@list');
    Route::post('/menu/add','Api\MenuController@add');
    Route::get('/menu/del','Api\MenuController@del');
    Route::post('/menu/edit','Api\MenuController@edit');
    Route::post('/menu/menurole','Api\MenuController@menuroles');
    Route::get('/menu/users','Api\MenuController@getusers');
    Route::get('/menu/menucode','Api\MenuController@maxmenucode');
    Route::get('/menu/pagefuns','Api\MenuController@pagefuns');
    Route::get('/menu/disable','Api\MenuController@disable');
    Route::get('/menu/enable','Api\MenuController@enable');

    Route::post('/organize/list','Api\OrganizeController@list');
    Route::post('/organize/add','Api\OrganizeController@add');
    Route::get('/organize/del','Api\OrganizeController@del');
    Route::post('/organize/edit','Api\OrganizeController@edit');

    Route::get('/baseinfo/province','Api\BaseInfoController@province' );
    Route::get('/baseinfo/freshroute','Api\BaseInfoController@freshroute');
    Route::get('/baseinfo/routes','Api\BaseInfoController@routelist' );
    Route::get('/baseinfo/icons','Api\BaseInfoController@icons');
    Route::get('/baseinfo/funcodes','Api\BaseInfoController@funcods');
    Route::post('/baseinfo/addfuncode','Api\BaseInfoController@addfuncode');
    Route::get('/baseinfo/delfuncode','Api\BaseInfoController@delfuncode');
    Route::get('/baseinfo/menutypelist','Api\BaseInfoController@menutypelist');

});

Route::post('/login','Api\UserController@login');

