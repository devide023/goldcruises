<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBaseinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('city');
        Schema::create('city', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pid')->nullable()->default(0)->comment('父id');
            $table->string('name',100)->nullable()->default(null)->comment('名称');
            $table->integer('status')->nullable()->default(1)->comment('状态');
            $table->string('code',10)->nullable()->default(null)->comment('地区编码');
        });

        Schema::dropIfExists('sex');
        Schema::create('sex',function (Blueprint $table){
            $table->increments('id');
            $table->integer('status')->nullable()->default(1)->comment('状态');
            $table->integer('code')->nullable()->default(1)->comment('性别编码');
            $table->string('name')->nullable()->default(null)->comment('名称');
        });

        Schema::dropIfExists('config');
        Schema::create('config',function (Blueprint $table){
            $table->increments('id');
            $table->integer('status')->nullable()->default(1)->comment('状态');
            $table->string('val',5000)->nullable()->default(null)->comment('配置值');
            $table->text('note')->nullable(true)->comment('备注');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作人');
            $table->dateTime('addtime')->nullable()->default(now())->comment('操作日期');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('city');
        Schema::dropIfExists('sex');
        Schema::dropIfExists('config');
    }
}
