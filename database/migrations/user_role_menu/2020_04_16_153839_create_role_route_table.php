<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleRouteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('roleroute');
        Schema::create('roleroute', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->nullable()->default(1);
            $table->integer('roleid')->nullable()->default(null)->comment('角色id');
            $table->integer('routeid')->nullable()->default(null)->comment('路由id');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roleroute');
    }
}
