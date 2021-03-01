<?php
//+----------------------------------------------------------------------
// |
//+----------------------------------------------------------------------
// | Author: Bennent <guolixun@gdi.com.cn>
//+----------------------------------------------------------------------
// | Date: 2020/8/8
//+----------------------------------------------------------------------
namespace Bennent\Geauth\Controllers;

use Bennent\Geauth\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends AdminBaseController
{
    //登录
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $params = $request->all();
            //账户密码验证
            $user = Manager::where('name', $params['username'])->first();
            if (!$user || !verify_pass($params['password'], $user->password)) return response()->json(['code' => 0, 'msg' => '账户密码不匹配']);
            Session::put('users', $user->toArray());
            return response()->json(['code' => 1]);
        }
        return view('geauth::admin.login.login');
    }

    //注销
    public function logout()
    {
        Session::flush();
        return true;
    }
}
