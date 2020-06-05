<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSqlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sql', function (Blueprint $table) {
            $table->increments('id');
            $table->text('tsql')->nullable()->default(null)->comment('sql语句');
            $table->text('note')->nullable()->default(null)->comment('备注');
            $table->string('action')->nullable()->default(null)->comment('方法');
            $table->string('controller')->nullable()->default(null)->comment('控制器');
            $table->string('url')->nullable()->default(null)->comment('路由地址');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作人');
            $table->dateTime('addtime')->nullable()->default(now())->comment('操作日期');
        });

        Schema::create('sqlparam',function (Blueprint $table){
            $table->increments('id');
            $table->integer('sqlid')->nullable()->default(null)->comment('sql语句id');
            $table->string('paramname')->nullable()->default(null)->comment('参数名称');
            $table->string('paramtype')->nullable()->default(null)->comment('参数类型');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sql');
        Schema::dropIfExists('sqlparam');
    }
}
