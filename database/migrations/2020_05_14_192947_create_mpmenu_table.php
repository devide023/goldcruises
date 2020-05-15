<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpmenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpmenu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->default(null)->comment('名称');
            $table->string('icon')->nullable()->default(null)->comment('图标名称');
            $table->integer('size')->nullable()->default(40)->comment('小程序页面路径');
            $table->string('color')->nullable()->default('#007AFF')->comment('图标大小');
            $table->string('pagepath')->nullable()->default(null)->comment('图标颜色');
            $table->dateTime('addtime')->nullable()->default(null)->comment('录入日期');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作员');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mpmenu');
    }
}
