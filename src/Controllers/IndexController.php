<?php
//+----------------------------------------------------------------------
// | 首页
//+----------------------------------------------------------------------
// | Author: Bennent <guolixun@gdi.com.cn>
//+----------------------------------------------------------------------
// | Date: 2020/7/31
//+----------------------------------------------------------------------

namespace Bennent\Geauth\Controllers;

use Bennent\Geauth\Models\Manager;
use Illuminate\Http\Request;

class IndexController extends AdminBaseController
{
    //首页
    public function index()
    {
        return view('geauth::admin.index.index', [
            'menus' => Manager::hasMenus()
        ]);
    }

    //控制台1
    public function console()
    {
        return view('geauth::admin.index.console');
    }

    //控制台2
    public function console1()
    {
        return view('geauth::admin.index.show');
    }

}
