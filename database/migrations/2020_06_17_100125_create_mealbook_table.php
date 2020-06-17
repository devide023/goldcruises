<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealbookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('status')->nullable()->default(1)->comment('状态');
            $table->string('name')->nullable()->default(null)->comment('套餐名称');
            $table->decimal('price', 18, 2)->nullable()->default(null)->comment('单价');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作人');
            $table->dateTime('addtime')->nullable()->default(now())->comment('操作日期');
            $table->integer('orgid')->nullable()->default(null)->comment('组织id');
        });

        Schema::create('mealbook', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('status')->nullable()->default(1)->comment('状态');
            $table->string('bookname')->nullable()->default(null)->comment('预订人');
            $table->string('booktel')->nullable()->default(null)->comment('预订电话');
            $table->decimal('amount',18,2)->nullable()->default(null)->comment('费用');
            $table->integer('ispayed')->nullable()->default(null)->comment('付款标志');
            $table->string('booknote')->nullable()->default(null)->comment('预订备注');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作人');
            $table->dateTime('addtime')->nullable()->default(now())->comment('操作日期');
            $table->integer('orgid')->nullable()->default(null)->comment('组织id');

        });
        Schema::create('mealbookdetail', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('bookid')->nullable()->default(null)->comment('预订id');
            $table->integer('mealid')->nullable()->default(null)->comment('套餐id');
            $table->decimal('price', 18, 2)->nullable()->default(null)->comment('套餐价格');
            $table->decimal('qty', 18, 2)->nullable()->default(null)->comment('数量');
            $table->decimal('amount', 18, 2)->nullable()->default(null)->comment('金额');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meal');
        Schema::dropIfExists('mealbook');
        Schema::dropIfExists('mealbookdetail');
    }
}
