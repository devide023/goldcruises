<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairbaseinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repairstatus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable()->default(null)->comment('编码');
            $table->string('name')->nullable()->default(null)->comment('名称');
            $table->integer('seq')->nullable()->default(10)->comment('序号');
            $table->integer('adduserid')->nullable()->default(null)->comment('录入人');
            $table->dateTime('addtime')->nullable()->default(now())->comment('操作日期');
        });
        Schema::create('repairtype', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable()->default(null)->comment('编码');
            $table->string('name')->nullable()->default(null)->comment('名称');
            $table->integer('seq')->nullable()->default(10)->comment('序号');
            $table->integer('adduserid')->nullable()->default(null)->comment('录入人');
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
        Schema::dropIfExists('repairstatus');
        Schema::dropIfExists('repairtype');
    }
}
