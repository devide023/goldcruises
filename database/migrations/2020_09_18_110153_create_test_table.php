<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hcbh')->nullable()->default(null)->comment('航次编号');
            $table->string('name')->nullable()->default(null)->comment('客人姓名');
            $table->string('certno')->nullable()->default(null)->comment('证件编号');
            $table->integer('fromdb')->nullable()->default(null)->comment('系统数据库');
            $table->dateTime('addtime')->nullable()->default(now())->comment('录入日期');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test');
    }
}
