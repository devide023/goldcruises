<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessstepTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('processstep');
        Schema::create('processstep', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('processid')->nullable()->default(null)->comment('流程id');
            $table->foreign('processid')
                ->references('id')
                ->on('process')
                ->onDelete('cascade');
            $table->integer('stepno')->nullable()->default(null)->comment('流程步骤');
            $table->string('stepname')->nullable()->default(null)->comment('步骤名称');
            $table->dateTime('addtime')->nullable()->default(null)->comment('操作时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processstep');
    }
}
