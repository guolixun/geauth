<?php
//+----------------------------------------------------------------------
// | 用户模型
//+----------------------------------------------------------------------
// | Author: Bennent <guolixun@gdi.com.cn>
//+----------------------------------------------------------------------
// | Date: 2021/2/26
//+----------------------------------------------------------------------

namespace Bennent\Geauth\Models;

use Illuminate\Database\Eloquent\Model;

class RoleManager extends Model
{
    protected $table = 'role_has_users';

    protected $guarded = [];

}
