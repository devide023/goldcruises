<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccesstokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accesstoken', function (Blueprint $table) {
            $table->increments('id');
            $table->string('access_token',500)->nullable()->comment('access_token');
            $table->dateTime('addtime')->default(now())->nullable()->comment('添加时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accesstoken');
    }
}
