<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->nullable()->default(1);
            $table->string('route', 500)->nullable()->default(null)->comment('路由');
            $table->string('group',100)->nullable()->default(null)->comment('分组');
            $table->string('note',500)->nullable()->default(null)->comment('备注');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作员');
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
        Schema::dropIfExists('route');
    }
}
