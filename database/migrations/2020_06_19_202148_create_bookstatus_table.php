<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookstatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookstatus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('statusid')->nullable()->default(null)->comment('状态id');
            $table->string('name')->nullable()->default(null)->comment('状态名称');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookstatus');
    }
}
