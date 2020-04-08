<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user');
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('status')->default(1)->comment('状态');
            $table->string('usercode',10)->comment('用户编码');
            $table->string('name',50)->comment('姓名');
            $table->string('userpwd','500')->default(encrypt('123456'))->comment('用户密码');
            $table->smallInteger('sex')->default(1)->comment('性别');
            $table->date('birthdate')->nullable(true)->comment('生日');
            $table->string('idno',20)->nullable(true)->comment('身份证号码');
            $table->string('tel',20)->nullable(true)->comment('电话');
            $table->string('email',100)->nullable(true)->comment('邮箱');
            $table->text('adress')->nullable(true)->comment('地址');
            $table->string('headimg',500)->nullable(true)->comment('头像文件名');
            $table->string('api_token',500)->nullable(true)->comment('token');
            $table->integer('province')->nullable(true)->comment('省');
            $table->integer('city')->nullable(true)->comment('市');
            $table->integer('district')->nullable(true)->comment('区');
            $table->integer('adduserid')->default(1)->comment('操作人');
            $table->dateTime('addtime')->default(now())->comment('录入时间');
        });

        Schema::dropIfExists('role');
        Schema::create('role',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('status')->default(1)->comment('状态');
            $table->string('name',100)->comment('角色名称');
            $table->text('note')->nullable(true)->comment('备注');
            $table->integer('adduserid')->comment('操作人');
            $table->dateTime('addtime')->default(now())->comment('操作日期');
        });

        Schema::dropIfExists('menu');
        Schema::create('menu',function (Blueprint $table ){
            $table->bigIncrements('id');
            $table->bigInteger('pid')->comment('父id');
            $table->string('name',100)->comment('菜单名称');
            $table->string('menucode',50)->comment('菜单编码');
            $table->string('menutype',10)->comment('菜单类型');
            $table->string('icon',50)->comment('图标');
            $table->integer('status')->default(1)->comment('状态');
            $table->integer('adduserid')->comment('操作人');
            $table->string('path',100)->nullable()->default(null)->comment('路由路径');
            $table->string('viewpath',100)->nullable()->default(null)->comment('视图路径');
            $table->dateTime('addtime')->default(now())->comment('操作日期');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
        Schema::dropIfExists('role');
        Schema::dropIfExists('menu');
    }
}
