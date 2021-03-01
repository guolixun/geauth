<?php
namespace Bennent\Geauth\Models;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        // create super administrator
        Manager::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => encrypt_pass('admin')
        ]);

        // create init menu
        DB::table(config('admin.database.menu_table'))->insert([
            ['pid' => 0, 'name' =>'权限管理', 'url' => 'admin/permission/#', 'icon' => 'layui-icon layui-icon-auz', 'status' => 1, 'order' => 1],
            ['pid' => 1, 'name' =>'菜单管理', 'url' => 'admin/permission', 'icon' => 'layui-icon layui-icon-tabs', 'status' => 1, 'order' => 1],
            // 菜单管理下子集菜单
            // ...
            ['pid' => 1, 'name' =>'角色管理', 'url' => 'admin/role', 'icon' => 'layui-icon layui-icon-component', 'status' => 1, 'order' => 2],
            // 角色管理下子集菜单
            // ...
            ['pid' => 1, 'name' =>'管理员', 'url' => 'admin/manager', 'icon' => 'layui-icon layui-icon-group', 'status' => 1, 'order' => 3],
            // 管理员下子集菜单
            // ...
        ]);
    }
}
