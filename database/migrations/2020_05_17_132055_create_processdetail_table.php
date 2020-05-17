<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessdetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processinfo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('processid')->nullable()->default(null)->comment('流程id');
            $table->integer('billid')->nullable()->default(null)->comment('单据id');
            $table->integer('stepno')->nullable()->default(null)->comment('当前步骤');
            $table->integer('flow')->nullable()->default(null)->comment('流程流向');
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
        Schema::dropIfExists('processdetail');
    }
}
