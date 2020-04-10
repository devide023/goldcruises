<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuncodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcode', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->nullable()->default(1)->comment('状态');
            $table->string('code',10)->unique()->nullable()->default(null)->comment('功能编码');
            $table->string('name',50)->nullable()->default(null)->comment('功能名称');
            $table->dateTime('addtime')->default(now())->comment('操作日期');
            $table->integer('adduserid')->nullable()->default(1)->comment('操作人');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('funcode');
    }
}
