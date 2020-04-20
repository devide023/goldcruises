<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIconTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('icon');
        Schema::create('icon', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->nullable()->default(1)->comment('状态');
            $table->string('name',50)->nullable()->default(null)->comment('图标名称');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('icon');
    }
}
