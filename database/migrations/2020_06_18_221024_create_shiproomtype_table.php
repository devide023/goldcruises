<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiproomtypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shiproomtype', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('roomtypeid')->nullable()->default(null)->comment('房型id');
            $table->string('shipno')->nullable()->default(null)->comment('邮轮编号');
            $table->decimal('price',18,2)->nullable()->default(null)->comment('单价');
            $table->integer('addusrid')->nullable()->default(null)->comment('操作人');
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
        Schema::dropIfExists('shiproomtype');
    }
}
