<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessstepuserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('processstepuser');
        Schema::create('processstepuser', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('processstepid')->nullable()->default(null)->comment('流程id');
            $table->foreign('processstepid')
                ->references('id')
                ->on('processstep')
                ->onDelete('cascade');
            $table->integer('userid')->nullable()->default(null)->comment('用户id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processstepuser');
    }
}
