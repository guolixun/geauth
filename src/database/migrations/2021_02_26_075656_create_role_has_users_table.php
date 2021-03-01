<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRoleHasUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_has_users', function (Blueprint $table) {
            $table->id();
            $table->integer('u_id')->comment('管理用户ID');
            $table->string('role_id')->nullable()->comment('角色ID,分割');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `role_has_users` comment '用户角色关系表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_has_users');
    }
}
