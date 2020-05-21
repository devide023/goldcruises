<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessogranizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('processogranize');
        Schema::create('processogranize', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('processid')->nullable()->default(null)->comment('流程id');
            $table->integer('orgid')->nullable()->default(null)->comment('组织节点id');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作人');
            $table->dateTime('addtime')->nullable()->default(null)->comment('操作日期');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processogranize');
    }
}
