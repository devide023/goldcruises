<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('repair');
        Schema::create('repair', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status')->nullable()->default(null)->comment('状态');
            $table->string('repairno')->nullable()->default(null)->comment('维修单号');
            $table->string('title')->nullable()->default(null)->comment('维修主题');
            $table->text('content')->nullable()->default(null)->comment('报修说明');
            $table->integer('adduserid')->nullable()->default(null)->comment('报修人id');
            $table->string('adduser')->nullable()->default(null)->comment('报修人');
            $table->string('addusertel')->nullable()->default(null)->comment('报修人电话');
            $table->dateTime('addtime')->nullable()->default(null)->comment('报修时间');
            $table->integer('dealuserid')->nullable()->default(null)->comment('处理人id');
            $table->string('dealperson')->nullable()->default(null)->comment('处理人');
            $table->string('dealpersontel')->nullable()->default(null)->comment('处理人电话');
            $table->dateTime('sendtime')->nullable()->default(null)->comment('派单时间');
            $table->integer('senduserid')->nullable()->default(null)->comment('派单人id');
            $table->string('sendperson')->nullable()->default(null)->comment('派单人');
            $table->dateTime('endtime')->nullable()->default(null)->comment('完结日期');
            $table->integer('enduserid')->nullable()->default(null)->comment('完结操作人id');
            $table->string('enduser')->nullable()->default(null)->comment('完结操作人');
            $table->text('note')->nullable()->default(null)->comment('备注');
        });
        Schema::dropIfExists('repairimage');
        Schema::create('repairimage',function (Blueprint $table){
            $table->increments('id');
            $table->integer('repairid')->nullable()->default(null)->comment('维修单id');
            $table->string('filename')->nullable()->default(null)->comment('文件名');
            $table->string('filetype')->nullable()->default(null)->comment('文件类型');
            $table->string('originalname')->nullable()->default(null)->comment('原文件名');
            $table->dateTime('addtime')->nullable()->default(null)->comment('上传时间');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作人');

        });
        Schema::dropIfExists('repairdetail');
        Schema::create('repairdetail',function (Blueprint $table){
            $table->increments('id');
            $table->integer('repairid')->nullable()->default(null)->comment('维修单id');
            $table->text('content')->nullable()->default(null)->comment('处理意见');
            $table->integer('dealuserid')->nullable()->default(null)->comment('处理人');
            $table->dateTime('dealtime')->nullable()->default(null)->comment('处理时间');
        });
        Schema::dropIfExists('repairdetailimg');
        Schema::create('repairdetailimg',function (Blueprint $table){
            $table->increments('id');
            $table->integer('detailid')->nullable()->default(null)->comment('维修详情id');
            $table->string('filename')->nullable()->default(null)->comment('文件名');
            $table->string('filetype')->nullable()->default(null)->comment('文件类型');
            $table->string('originalname')->nullable()->default(null)->comment('原文件名');
            $table->dateTime('addtime')->nullable()->default(null)->comment('上传时间');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作人');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repair');
        Schema::dropIfExists('repairimage');
        Schema::dropIfExists('repairdetail');
        Schema::dropIfExists('repairdetailimg');
    }
}
