<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpuserroleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpuserfun', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userid')->nullable()->default(null)->comment('用户id');
            $table->integer('funid')->nullable()->default(null)->comment('功能id');
        });
        Schema::create('mpusermenu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userid')->nullable()->default(null)->comment('用户id');
            $table->integer('mpmenuid')->nullable()->default(null)->comment('功能id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mpuserfun');
        Schema::dropIfExists('mpusermenu');
    }
}
