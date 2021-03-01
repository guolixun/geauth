<?php
//+----------------------------------------------------------------------
// |
//+----------------------------------------------------------------------
// | Author: Bennent <guolixun@gdi.com.cn>
//+----------------------------------------------------------------------
// | Date: 2020/8/7
//+----------------------------------------------------------------------
namespace Bennent\Geauth\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{

    protected $table = 'role_has_permissions';

    protected $guarded = [];
}
