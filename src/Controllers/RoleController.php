<?php

namespace Bennent\Geauth\Controllers;

use Bennent\Geauth\Models\Permission;
use Bennent\Geauth\Models\RolePermission;
use Illuminate\Http\Request;
use Bennent\Geauth\Models\Role;
//use Spatie\Permission\Models\Role as AuthRole;
use Illuminate\Support\Facades\DB;

/**
 * Class RoleController
 * @adminMenuRoot(
 *     'name'   => '权限管理',
 *     'action' => 'default',
 *     'parent' => 'admin/role/#',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   => '',
 *     'remark' => '权限管理'
 * )
 */
class RoleController extends AdminBaseController
{
    /**
     * 角色管理
     * @adminMenu(
     *     'name'   => '角色管理',
     *     'parent' => 'admin/role/#',
     *     'router' => 'admin/role',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 50,
     *     'icon'   => 'layui-icon layui-icon-component',
     *     'remark' => '角色管理',
     *     'param'  => ''
     * )
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $page  = $request->input('page') ? $request->input('page') : 1;
            $limit = $request->input('limit') ? $request->input('limit') : 15;
            $roles = Role::paginate($limit, ['*'], 'page', $page)->toArray();
            return response()->json([
                'code'  => 0,
                'msg'   => '',
                'count' => Role::count(),
                'data'  => $roles['data']
            ]);
        }
        return view('geauth::admin.role.index');
    }

    /**
     * 创建角色
     * @adminMenu(
     *     'name'   => '创建角色',
     *     'parent' => 'admin/role',
     *     'router' => 'admin/role/create',
     *     'display'=> '',
     *     'hasView'=> false,
     *     'order'  => 50,
     *     'icon'   => '',
     *     'remark' => '创建角色',
     *     'param'  => ''
     * )
     */
    public function create()
    {
        if (request()->isMethod('post')) {
            $params = request()->all();
            $role   = Role::create([
                'name'   => $params['name'],
                'status' => $params['status']
            ]);
            return response()->json($role->id ? 1 : 0);
        }
        $permissions = Permission::select('id', 'name')->get();
        return view('geauth::admin.role.create', ['permissions' => $permissions]);
    }

    /**
     * 编辑角色
     * @adminMenu(
     *     'name'   => '编辑角色',
     *     'parent' => 'admin/role',
     *     'router' => 'admin/role/edit',
     *     'display'=> '',
     *     'hasView'=> false,
     *     'order'  => 50,
     *     'icon'   => '',
     *     'remark' => '编辑角色',
     *     'param'  => ''
     * )
     */
    public function edit($id = 0)
    {
        if (request()->isMethod('put') || request()->isMethod('post')) {
            //更新
            $params = request()->all();
            $roles  = Role::where('id', request()->input('id', 0))->update($params['data']);
            return response()->json($roles !== false ? 1 : 0 );
        }
        $role = Role::find($id);
        return view('geauth::admin.role.edit', [
            'role' => $role
        ]);
    }

    /**
     * @notes:角色删除
     * @user: Bennent_G
     * @param Role $role
     */
    public function destroy(Role $role)
    {
        //---------验证角色下用户-------------//
        // ...
        //---------验证角色下用户-------------//
        $resp = $role::destroy(request()->input('id', 0));
        return response()->json(['code' => 1, 'msg' => '操作成功']);
    }

    /**
     * 角色权限
     * @adminMenu(
     *     'name'   => '角色权限',
     *     'parent' => 'admin/role',
     *     'router' => 'admin/role/edit',
     *     'display'=> '',
     *     'hasView'=> false,
     *     'order'  => 50,
     *     'icon'   => '',
     *     'remark' => '角色权限',
     *     'param'  => ''
     * )
     */
    public function read($rid = 0, Role $role)
    {
        if (request()->isMethod('post')) {
            $rid             = request()->input('rid');
            $permission      = DB::table('permissions')->select('id', 'pid', 'name', DB::raw('"true" as open'))->get()
                ->map(function ($value) {
                    return (array)$value;
                })
                ->toArray();
            $checkPermission = RolePermission::where('role_id', $rid)->pluck('permission_id')->toArray();
            foreach ($permission as $key => $val) {
                if (in_array($val['id'], $checkPermission))
                    $permission[$key]['checked'] = 'true';
            }
            $permissions = Permission::arr2tree($permission, 'id', 'pid', 'children');
            return response()->json($permissions);
        }
        return view('geauth::admin.role.show', ['rid' => $rid]);
    }

    /**
     * 更新授权
     * @adminMenu(
     *     'name'   => '更新授权',
     *     'parent' => 'admin/role',
     *     'router' => 'admin/role/auth',
     *     'display'=> '',
     *     'hasView'=> false,
     *     'order'  => 50,
     *     'icon'   => '',
     *     'remark' => '更新授权',
     *     'param'  => ''
     * )
     */
    public function auth(Request $request)
    {
        $params = $request->all();
        RolePermission::where('role_id', $params['rid'])->delete();
        $data = [];
        foreach ($params['nodes'] as $k => $permission) {
            $data[$k]['role_id']       = $params['rid'];
            $data[$k]['permission_id'] = $permission;
        }
        $resp = RolePermission::insert($data);
        return response()->json($resp);
    }
}
