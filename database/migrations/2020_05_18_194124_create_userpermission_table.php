<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserpermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userpermission', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userid')->nullable()->default(null)->comment('用户id');
            $table->integer('orgid')->nullable()->default(null)->comment('组织节点');
            $table->integer('adduserid')->nullable()->default(null);
            $table->dateTime('addtime')->nullable()->default(now());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userpermission');
    }
}
