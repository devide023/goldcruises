<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * 合同主表
         */
        Schema::dropIfExists('contract');
        Schema::create('contract', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status')->nullable()->default(null)->comment('合同状态');
            $table->string('contractno')->nullable()->default(null)->comment('合同编号');
            $table->string('name')->nullable()->default(null)->comment('合同名称');
            $table->string('type')->nullable()->default(null)->comment('合同类型');
            $table->string('cntidentity')->nullable()->default(null)->comment('我方身份');
            $table->decimal('amount',18,2)->nullable()->default(null)->comment('合同金额');
            $table->decimal('payedamount',18,2)->nullable()->default(null)->comment('已付金额');
            $table->string('payway')->nullable()->default(null)->comment('支付方式');
            $table->string('contractcompany')->nullable()->default(null)->comment('承办单位');
            $table->string('contractor')->nullable()->default(null)->comment('承办人');
            $table->string('contractortel')->nullable()->default(null)->comment('承办人系方式');
            $table->dateTime('signdate')->nullable()->default(null)->comment('签订日期');
            $table->date('bdate')->nullable()->default(null)->comment('合同开始日期');
            $table->date('edate')->nullable()->default(null)->comment('合同结束日期');
            $table->string('moneyflow')->nullable()->default(null)->comment('资金流向');
            $table->string('dutyperson')->nullable()->default(null)->comment('合同负责人');
            $table->string('dutypersontel')->nullable()->default(null)->comment('合同负责人电话');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作人');
            $table->dateTime('addtime')->nullable()->default(null)->comment('操作日期');
        });
        /*
         * 合同附件
         */
        Schema::dropIfExists('contractfiles');
        Schema::create('contractfiles',function (Blueprint $table){
            $table->increments('id');
            $table->integer('status')->nullable()->default(1)->comment('状态');
            $table->integer('contractid')->nullable()->default(null)->comment('合同id');
            $table->string('file')->nullable()->default(null)->comment('合同文件名');
            $table->string('filetype')->nullable()->default(null)->comment('文件类型');
            $table->string('filesize')->nullable()->default(null)->comment('文件大小');
            $table->string('filename')->nullable()->default(null)->comment('客户端文件名');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作员');
            $table->dateTime('addtime')->nullable()->default(now())->comment('操作日期');
        });
        /*
         * 合同状态
         */
        Schema::dropIfExists('contractstatus');
        Schema::create('contractstatus',function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->nullable()->default(null)->comment('状态名称');
            $table->string('code')->nullable()->default(null)->comment('状态编码');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作员');
            $table->dateTime('addtime')->nullable()->default(now())->comment('操作日期');
        });
        /*
         * 合同类型
         */
        Schema::dropIfExists('contracttype');
        Schema::create('contracttype',function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->nullable()->default(null)->comment('类型名称');
            $table->string('code')->nullable()->default(null)->comment('类型编码');
            $table->integer('adduserid')->nullable()->default(null)->comment('操作员');
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
        Schema::dropIfExists('contract');
        Schema::dropIfExists('contractfiles');
        chema::dropIfExists('contractstatus');
        Schema::dropIfExists('contracttype');
    }
}
