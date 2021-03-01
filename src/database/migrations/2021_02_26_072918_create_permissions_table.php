<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('pid')->nullable(false)->default(0)->comment('父ID默认0');
            $table->string('name', 200)->nullable(false)->comment('节点名称');
            $table->string('url')->nullable(false)->comment('节点URL');
            $table->string('icon')->nullable()->comment('节点ICON');
            $table->integer('order')->nullable(false)->default(100)->comment('节点排序默认100');
            $table->tinyInteger('status')->nullable(false)->default(1)->comment('节点状态默认1:正常0:禁用');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `permissions` comment '权限菜单表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
