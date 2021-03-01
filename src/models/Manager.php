<?php
//+----------------------------------------------------------------------
// | 用户模型
//+----------------------------------------------------------------------
// | Author: Bennent <guolixun@gdi.com.cn>
//+----------------------------------------------------------------------
// | Date: 2021/2/26
//+----------------------------------------------------------------------

namespace Bennent\Geauth\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Manager extends Model
{
    protected $table = 'managers';

    protected $guarded = [];

    // 重置时间序列化
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function hasMenus()
    {
        if (Session::get('users.id') == 1 && Session::get('users.name') == 'admin') {
            //超级管理员获取全部权限
            $permissions = Permission::select('id', 'pid', 'name', 'url', 'icon')
                ->where('status', 1)
                ->orderBy('order')
                ->get()->toArray();
        } else {
            //不使用session,预防修改用户权限后用户在不重新登录的情况下无法获取新的授权。
            $role_id = self::where('id', Session::get('users.id'))->value('role_id');
            $permissions = RolePermission::leftJoin('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->whereIn('role_id', explode(',', $role_id))
                ->select('permissions.id', 'permissions.pid', 'name', 'url', 'icon')
                ->groupBy('permissions.id')
                ->get()
                ->toArray();
        }
        $menus = permission::arr2tree($permissions, 'id', 'pid', 'children');
        return $menus;
    }
}
