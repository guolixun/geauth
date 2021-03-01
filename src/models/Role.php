<?php

namespace Bennent\Geauth\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $table = 'roles';

    protected $guarded = [];

    // 重置时间序列化
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
