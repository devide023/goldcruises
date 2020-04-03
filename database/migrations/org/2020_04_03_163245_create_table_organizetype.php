<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOrganizetype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('organizetype');
        Schema::create('organizetype', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('status')->nullable()->default(1);
            $table->string('code', 10)->nullable()->default(null)->comment('编码');
            $table->string('name','100')->nullable()->default(null)->comment('名称');
            $table->dateTime('addtime')->default(now())->comment('操作日期');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizetype');
    }
}
