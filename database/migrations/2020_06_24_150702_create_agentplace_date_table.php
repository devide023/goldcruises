<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentplaceDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agentplace_date', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->nullable()->default(1)->comment('状态');
            $table->dateTime('bdate')->nullable()->default(null)->comment('开始日期');
            $table->dateTime('edate')->nullable()->default(null)->comment('结束日期');
            $table->string('shipno')->nullable()->default(null)->comment('邮轮编号');
            $table->integer('agentid')->nullable()->default(null)->comment('代理商id');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作人');
            $table->dateTime('addtime')->nullable()->default(now())->comment('操作日期');
        });

        Schema::create('agentplace_date_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('placedateid')->nullable()->default(null)->comment('控位日期id');
            $table->integer('roomtypeid')->nullable()->default(null)->comment('房型id');
            $table->decimal('qty',18,2)->nullable()->default(null)->comment('位置数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agentplace_date');
        Schema::dropIfExists('agentplace_date_detail');
    }
}
