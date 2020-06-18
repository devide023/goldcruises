<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentplaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agentplace', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->nullable()->default(1)->comment('状态');
            $table->string('shipno')->nullable()->default(null)->comment('邮轮编号');
            $table->integer('agentid')->nullable()->default(null)->comment('代理商id');
            $table->string('agentname')->nullable()->default(null)->comment('代理商名称');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作人');
            $table->dateTime('addtime')->nullable()->default(now())->comment('操作日期');
        });
        Schema::create('agentplacedetail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agentplaceid')->nullable()->default(null)->comment('控位id');
            $table->integer('roomtypeid')->nullable()->default(null)->comment('房型id');
            $table->decimal('qty',18,2)->nullable()->default()->comment('控位数量');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agentplace');
        Schema::dropIfExists('agentplacedetail');
    }
}
