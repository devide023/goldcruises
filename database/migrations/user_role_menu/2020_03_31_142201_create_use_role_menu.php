<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUseRoleMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('roleuser');
        Schema::create('roleuser', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('roleid')->comment('角色id');
            $table->integer('userid')->comment('用户id');
        });
        Schema::dropIfExists('rolemenu');
        Schema::create('rolemenu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('roleid')->comment('角色id');
            $table->integer('menuid')->comment('菜单id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roleuser');
        Schema::dropIfExists('rolemenu');
    }
}
