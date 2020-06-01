<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditdetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processdetail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('processid')->nullable()->default(null)->comment('流程id');
            $table->integer('stepno')->nullable()->default(null)->comment('步骤编号');
            $table->text('advise')->nullable()->default(null)->comment('意见建议');
            $table->integer('adduserid')->nullable()->default(null)->comment('审核人');
            $table->dateTime('addtime')->nullable()->default(now())->comment('审核日期');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processdetail');
    }
}
