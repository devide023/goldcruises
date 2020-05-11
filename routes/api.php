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
Route::any('test', 'Api\TestController@test');

Route::middleware('auth:api')->group(function ()
{
    /*
     * 用户
     */
    Route::post('/user/list', 'Api\UserController@list');
    Route::post('/user/add', 'Api\UserController@add');
    Route::get('/user/del', 'Api\UserController@del');
    Route::post('/user/userrole', 'Api\UserController@userroles');
    Route::post('/user/userorg', 'Api\UserController@userorgs');
    Route::get('/user/menus', 'Api\UserController@getusermenus');
    Route::get('/user/routes', 'Api\UserController@userroutes');
    Route::post('/user/edit', 'Api\UserController@edit');
    Route::post('/user/chpwd', 'Api\UserController@modifypwd');
    Route::get('user/info', 'Api\UserController@info');
    Route::post('/user/headimg', 'Api\UserController@upload_headimg');
    Route::get('/user/find', 'Api\UserController@findbyid');
    Route::post('/user/disable', 'Api\UserController@disable');
    Route::post('/user/enable', 'Api\UserController@enable');
    Route::any('/user/logout', 'Api\UserController@logout');
    Route::get('/user/get_userroles', 'Api\UserController@getuserroles');
    Route::get('/user/get_userorgs', 'Api\UserController@getuserorg');
    /*
     * 角色
     */
    Route::post('/role/list', 'Api\RoleController@list');
    Route::post('/role/add', 'Api\RoleController@add');
    Route::post('/role/edit', 'Api\RoleController@edit');
    Route::get('/role/del', 'Api\RoleController@del');
    Route::post('/role/roleuser', 'Api\RoleController@roleuser');
    Route::post('/role/rolemenu', 'Api\RoleController@rolemenu');
    Route::get('/role/users', 'Api\RoleController@getusers');
    Route::get('/role/menus', 'Api\RoleController@getmenus');
    Route::get('/role/rolerel', 'Api\RoleController@getrolerel');
    Route::get('/role/rolemenupath', 'Api\RoleController@rolemenupath');
    /*
     * 菜单
     */

    Route::post('/menu/list', 'Api\MenuController@list');
    Route::post('/menu/add', 'Api\MenuController@add');
    Route::get('/menu/del', 'Api\MenuController@del');
    Route::post('/menu/edit', 'Api\MenuController@edit');
    Route::post('/menu/menurole', 'Api\MenuController@menuroles');
    Route::get('/menu/users', 'Api\MenuController@getusers');
    Route::get('/menu/menucode', 'Api\MenuController@maxmenucode');
    Route::get('/menu/pagefuns', 'Api\MenuController@pagefuns');
    Route::get('/menu/disable', 'Api\MenuController@disable');
    Route::get('/menu/enable', 'Api\MenuController@enable');
    Route::get('/menu/all_menu_tree', 'Api\MenuController@all_menu_tree');
    /*
     * 组织节点
     */
    Route::post('/organize/list', 'Api\OrganizeController@list');
    Route::post('/organize/add', 'Api\OrganizeController@add');
    Route::post('/organize/saveallorg', 'Api\OrganizeController@saveallorg');
    Route::get('/organize/create_node', 'Api\OrganizeController@create_node');
    Route::get('/organize/remove_node', 'Api\OrganizeController@remove_node');
    Route::post('/organize/edited_node', 'Api\OrganizeController@edited_node');
    Route::get('/organize/del', 'Api\OrganizeController@del');
    Route::get('/organize/find', 'Api\OrganizeController@find');
    Route::post('/organize/edit', 'Api\OrganizeController@edit');
    Route::get('/organize/curentnodes', 'Api\OrganizeController@curentnodes');
    Route::get('/organize/alltree', 'Api\OrganizeController@alltree');
    Route::get('/organize/getorgusers', 'Api\OrganizeController@getorgusers');
    /*
     * 基础信息
     */
    Route::get('/baseinfo/province', 'Api\BaseInfoController@province');
    Route::get('/baseinfo/freshroute', 'Api\BaseInfoController@freshroute');
    Route::get('/baseinfo/routes', 'Api\BaseInfoController@routelist');
    Route::get('/baseinfo/icons', 'Api\BaseInfoController@icons');
    Route::get('/baseinfo/funcodes', 'Api\BaseInfoController@funcods');
    Route::post('/baseinfo/addfuncode', 'Api\BaseInfoController@addfuncode');
    Route::get('/baseinfo/delfuncode', 'Api\BaseInfoController@delfuncode');
    Route::get('/baseinfo/menutypelist', 'Api\BaseInfoController@menutypelist');
    Route::get('/baseinfo/orgtypes', 'Api\BaseInfoController@orgtypes');
    Route::get('/baseinfo/contracttypes', 'Api\BaseInfoController@contracttypes');
    Route::get('/baseinfo/contractstatus', 'Api\BaseInfoController@contractstatus');
    Route::get('/baseinfo/repairstatus', 'Api\BaseInfoController@repairstatus');
    Route::get('/baseinfo/repairtypes', 'Api\BaseInfoController@repairtypes');
    /*
     * 合同管理路由
     */
    Route::post('/contract/list', 'Api\Contract\ContractController@list');
    Route::post('/contract/add', 'Api\Contract\ContractController@add');
    Route::post('/contract/edit', 'Api\Contract\ContractController@edit');
    Route::post('/contract/upload','Api\Contract\ContractController@upload_contract');
    Route::post('/contract/delfile','Api\Contract\ContractController@remove_contractfile');
    Route::get('/contract/getfiles','Api\Contract\ContractController@getcontractfiles');
    Route::get('/contract/getcontractno', 'Api\Contract\ContractController@getcontractno');

    /*
     * 报修管理
     */
    Route::post('/repair/list','Api\Repair\RepairController@list');
    Route::post('/repair/add','Api\Repair\RepairController@add');
    Route::post('/repair/edit','Api\Repair\RepairController@edit');
    Route::get('/repair/find','Api\Repair\RepairController@find');
    Route::get('/repair/repairno','Api\Repair\RepairController@getrepairno');

});

Route::post('/login', 'Api\UserController@login');

