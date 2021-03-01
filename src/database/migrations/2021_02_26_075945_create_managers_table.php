<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('管理员名称');
            $table->string('email')->nullable()->comment('管理员邮箱');
            $table->string('mobile')->nullable()->comment('管理员手机');
            $table->string('password')->comment('管理员密码');
            $table->string('avatar')->nullable()->comment('管理员头像');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `managers` comment '管理员用户表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('managers');
    }
}
