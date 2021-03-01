<?php

namespace Bennent\Geauth\Controllers;

use Illuminate\Http\Request;
use Bennent\Geauth\Models\Role;
use Bennent\Geauth\Models\Manager;
use Bennent\Geauth\Models\RoleManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

/**
 * Class UserController
 * @adminMenuRoot(
 *     'name'   => '用户管理',
 *     'action' => 'default',
 *     'parent' => 'admin/users/#',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   => 'layui-icon layui-icon-group',
 *     'remark' => '权限管理'
 * )
 */
class ManagerController extends AdminBaseController
{

    /**
     * 网站用户
     * @adminMenu(
     *     'name'   => '网站用户',
     *     'parent' => 'admin/users/#',
     *     'router' => 'admin/users',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 50,
     *     'icon'   => 'layui-icon layui-icon-username',
     *     'remark' => '网站用户',
     *     'param'  => ''
     * )
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $page  = $request->input('page') ? $request->input('page') : 1;
            $limit = $request->input('limit') ? $request->input('limit') : 15;
            $users = Manager::paginate($limit, ['*'], 'page', $page)->toArray();
            return response()->json([
                'code'  => 0,
                'msg'   => '',
                'count' => Manager::count(),
                'data'  => $users['data']
            ]);
        }
        return view('geauth::admin.manager.index');
    }

    /**
     * 创建用户
     * @adminMenu(
     *     'name'   => '创建用户',
     *     'parent' => 'admin/users',
     *     'router' => 'admin/users/create',
     *     'display'=> '',
     *     'hasView'=> false,
     *     'order'  => 50,
     *     'icon'   => '',
     *     'remark' => '创建用户',
     *     'param'  => ''
     * )
     */
    public function create()
    {
        if (request()->isMethod('post')) {
            DB::beginTransaction();
            try {
                $params = request()->except('file');
                $manager = Manager::create([
                    'name' => $params['name'],
                    'email' => $params['email'],
                    'mobile' => $params['mobile'],
                    'password' => encrypt_pass($params['password']),
                    'avatar' => $params['avatar']
                ]);
                RoleManager::create([
                    'u_id' => $manager->id,
                    'role_id' => $params['role_id']
                ]);
                DB::commit();
                return response()->json(['code' => 1]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['code' => 0]);
            }
        }
        return view('geauth::admin.manager.create', [
            'roles' => Role::all()
        ]);
    }

    /**
     * 头像上传
     * @adminMenu(
     *     'name'   => '头像上传',
     *     'parent' => 'admin/users',
     *     'router' => 'admin/users/avatar',
     *     'display'=> '',
     *     'hasView'=> false,
     *     'order'  => 50,
     *     'icon'   => '',
     *     'remark' => '头像上传',
     *     'param'  => ''
     * )
     */
    public function avatar()
    {
        $path = Storage::disk('public')->putFile('avatars', request()->file('file'));
        return response()->json(['code' => 1, 'path' => $path]);
    }

    /**
     * 编辑用户
     * @adminMenu(
     *     'name'   => '编辑用户',
     *     'parent' => 'admin/users',
     *     'router' => 'admin/users/edit',
     *     'display'=> '',
     *     'hasView'=> false,
     *     'order'  => 50,
     *     'icon'   => '',
     *     'remark' => '编辑用户',
     *     'param'  => ''
     * )
     */
    public function edit($id = 0)
    {
        if (request()->isMethod('put')) {
            // 更新
            DB::beginTransaction();
            try {
                $params = request()->except('data.file');
                Manager::where('id', $params['data']['id'])->update([
                    'name'     => $params['data']['name'],
                    'email'    => $params['data']['email'],
                    'mobile'   => $params['data']['mobile'],
                    'avatar'   => $params['data']['avatar']
                ]);

                RoleManager::where('u_id', $params['data']['id'])->update([
                    'role_id' => $params['data']['role_id']
                ]);
                DB::commit();
                return response()->json(['code' => 1]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['code' => 0]);
            }
        }
        $user           = Manager::find($id);
        $user->role_ids = $user->role_id ? explode(',', $user->role_id) : [];
        //$user->avatar_  = $user->avatar ? asset('uploads/'.$user->avatar) : '';
        return view('geauth::admin.manager.edit', [
            'user'  => $user,
            'hasRole' => explode(',', RoleManager::where('u_id', $user->id)->value('role_id')),
            'roles' => Role::all()
        ]);
    }

    /**
     * @notes:用户删除
     * @user: Bennent_G
     * @param User $users
     */
    public function destroy()
    {
        Manager::destroy(request()->input('id', 0));
        return response()->json(['code' => 1, 'msg' => '操作成功']);
    }

    /**
     * 更新密码
     * @adminMenu(
     *     'name'   => '更新密码',
     *     'parent' => 'admin/users',
     *     'router' => 'admin/users/pass',
     *     'display'=> '',
     *     'hasView'=> false,
     *     'order'  => 50,
     *     'icon'   => '',
     *     'remark' => '更新密码',
     *     'param'  => ''
     * )
     */
    public function pass($uid = 0)
    {
        if (request()->isMethod('post')) {
            $params = request()->all();
            $user   = Manager::where('id', $params['id'])->update(['password' => encrypt_pass($params['password'])]);
            return response()->json($user);
        }
        return view('geauth::admin.manager.pass', [
            'user' => Manager::find($uid)
        ]);
    }
}
