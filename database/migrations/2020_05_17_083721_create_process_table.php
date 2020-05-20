<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->nullable()->default(1)->comment('状态');
            $table->string('name')->nullable()->default(null)->comment('流程名称');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作人');
            $table->dateTime('addtime')->nullable()->default(null)->comment('操作时间');
        });

        Schema::create('processstep', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('processid')->nullable()->default(null)->comment('流程id');
            $table->integer('stepno')->nullable()->default(null)->comment('流程步骤');
            $table->string('stepname')->nullable()->default(null)->comment('步骤名称');
            $table->dateTime('addtime')->nullable()->default(null)->comment('操作时间');
        });

        Schema::create('processstepuser', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('processstepid')->nullable()->default(null)->comment('步骤id');
            $table->integer('userid')->nullable()->default(null)->comment('用户id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process');
        Schema::dropIfExists('processstep');
        Schema::dropIfExists('processstepuser');
    }
}
