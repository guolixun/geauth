<?php

namespace Bennent\Geauth\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    protected $table = 'permissions';

    protected $guarded = [];

    // 重置时间序列化
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function arr2table(array $list, $cid = 'id', $pid = 'pid', $cpath = 'url', $ppath = '')
    {
        $tree = [];
        foreach (static::arr2tree($list, $cid, $pid) as $attr) {
            $attr[$cpath] = "{$ppath}-{$attr[$cpath]}";
            $attr['url_'] = substr($attr[$cpath], strripos ($attr[$cpath], '-')+1);
            $attr['sub'] = $attr['sub'] ?? [];
            $attr['spt'] = substr_count($ppath, '-');
            $attr['spl'] = str_repeat("　├　", $attr['spt']);
            $sub = $attr['sub'];
            unset($attr['sub']);
            $tree[] = $attr;
            if (!empty($sub)) $tree = array_merge($tree, static::arr2table($sub, $cid, $pid, $cpath, $attr[$cpath]));
        }
        return $tree;
    }

    /**
     * @notes:一维数组生成数据树
     * @param array  $list 待处理数据
     * @param string $cid  自己的主键
     * @param string $pid  上级的主键
     * @param string $sub  子数组名称
     * @return
     */
    public static function arr2tree($list, $cid = 'id', $pid = 'pid', $sub = 'sub')
    {
        list($tree, $tmp) = [[], array_combine(array_column($list, $cid), array_values($list))];
        foreach ($list as $vo) {
            isset($vo[$pid]) && isset($tmp[$vo[$pid]]) ? $tmp[$vo[$pid]][$sub][] = &$tmp[$vo[$cid]] : $tree[] = &$tmp[$vo[$cid]];
        }
        unset($tmp, $list);
        return $tree;
    }
}
