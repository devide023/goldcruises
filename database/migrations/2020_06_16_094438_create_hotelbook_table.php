<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelbookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roomtype', function (Blueprint $table){
            $table->increments('id');
            $table->integer('status')->nullable()->default(1)->comment('状态');
            $table->string('name')->nullable()->default(null)->comment('房型名称');
            $table->decimal('price',18,2)->nullable()->default(0.0)->comment('单价');
            $table->decimal('totalqty',18,2)->nullable()->default(null)->comment('总房间数');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作人id');
            $table->dateTime('addtime')->nullable()->default(null)->comment('操作日期');
        });

        Schema::create('hotelbook', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('bdate')->nullable()->default(null)->comment('预计入住日期');
            $table->dateTime('edate')->nullable()->default(null)->comment('预计退房日期');
            $table->string('bookname')->nullable()->default(null)->comment('预订人姓名');
            $table->integer('bookcount')->nullable()->default(null)->comment('预订人人数');
            $table->string('booktel')->nullable()->default(null)->comment('预订人电话');
            $table->decimal('amount',18,2)->nullable()->default(null)->comment('预订费用');
            $table->integer('ispayed')->nullable()->default(1)->comment('是否已付费用');
            $table->string('booknote')->nullable()->default(null)->comment('预订备注');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作人id');
            $table->dateTime('addtime')->nullable()->default(null)->comment('操作日期');
        });

        Schema::create('hotelbookdetail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bookid')->nullable()->default(null)->comment('预定id');
            $table->integer('roomtypeid')->nullable()->default(null)->comment('房型id');
            $table->integer('qty')->nullable()->default(null)->comment('预定数量');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roomtype');
        Schema::dropIfExists('hotelbook');
        Schema::dropIfExists('hotelbookdetail');
    }
}
