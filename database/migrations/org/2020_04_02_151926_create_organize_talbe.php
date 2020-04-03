<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizeTalbe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('organize');
        Schema::create('organize', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->integer('pid')->nullable()->default(null)->comment('父id');
            $table->string('name', 100)->nullable()->default(null)->comment('组织节点名称');
            $table->string('orgcode',100)->nullable()->default(null)->comment('节点编码');
            $table->integer('status')->nullable()->default(1)->comment('状态');
            $table->string('orgtype',10)->nullable()->default(null)->comment('节点类型');
            $table->string('leader')->nullable(true)->comment('领导人/法人');
            $table->string('logo',1000)->nullable()->default(null)->comment('节点标志');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作人');
            $table->dateTime('addtime')->nullable()->default(now())->comment('操作时间');
        });
        Schema::dropIfExists('userorg');
        Schema::create('userorg', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->integer('userid')->nullable()->default(null)->comment('用户id');
            $table->integer('orgid')->nullable()->default(null)->comment('组织节点id');
            $table->integer('main')->nullable()->default(0)->comment('主节点');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作人');
            $table->dateTime('addtime')->nullable()->default(now())->comment('操作时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organize');
        Schema::dropIfExists('userorg');
    }
}
