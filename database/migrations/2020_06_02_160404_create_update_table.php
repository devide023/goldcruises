<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('update');
        Schema::create('update', function (Blueprint $table) {
            $table->increments('id');
            $table->string('appid')->nullable()->default(null)->comment('应用id');
            $table->string('apkfilename')->nullable()->default(null)->comment('apk包名');
            $table->string('ipkfilename')->nullable()->default(null)->comment('ipk包名');
            $table->string('version')->nullable()->default(null)->comment('版本号');
            $table->dateTime('addtime')->nullable()->default(now());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('update');
    }
}
