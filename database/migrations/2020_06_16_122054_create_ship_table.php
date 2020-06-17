<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->nullable()->default(null)->comment('状态');
            $table->string('code')->nullable()->default(null)->comment('邮轮编号');
            $table->string('name')->nullable()->default(null)->comment('邮轮名称');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ship');
    }
}
