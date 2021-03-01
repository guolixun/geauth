<?php
//+----------------------------------------------------------------------
// | 系统管理
//+----------------------------------------------------------------------
// | Author: Bennent <guolixun@gdi.com.cn>
//+----------------------------------------------------------------------
// | Date: 2021/2/2
//+----------------------------------------------------------------------
namespace Bennent\Geauth\Controllers;

//use App\Http\Traits\MenuTraits;
use Bennent\Geauth\Models\permission;
use Illuminate\Http\Request;
//use Spatie\Permission\Models\Permission as AuthPermission;

/**
 * Class PermissionController
 * @adminMenuRoot(
 *     'name'   => '系统管理',
 *     'action' => 'default',
 *     'parent' => 'admin/setting/#',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   => '',
 *     'remark' => '系统管理'
 * )
 */
class PermissionController extends AdminBaseController
{

    /**
     * 菜单管理
     * @adminMenu(
     *     'name'   => '菜单管理',
     *     'parent' => 'admin/setting/#',
     *     'router' => 'admin/permission',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 50,
     *     'icon'   => '',
     *     'remark' => '菜单管理',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        if (request()->isMethod('post')) {
            $lists       = Permission::select('id', 'pid', 'name', 'url', 'icon', 'order', 'updated_at')->get()->toArray();
            $permissions = permission::arr2table($lists);
            return response()->json([
                'code'  => 0,
                'msg'   => '',
                'count' => count($permissions),
                'data'  => $permissions
            ]);
        }
        return view('geauth::admin.permission.index');
    }

    /**
     * 创建菜单
     * @adminMenu(
     *     'name'   => '创建菜单',
     *     'parent' => 'admin/permission',
     *     'router' => 'admin/permission/create',
     *     'display'=> '',
     *     'hasView'=> false,
     *     'order'  => 50,
     *     'icon'   => '',
     * )
     */
    public function create($id = 0)
    {
        if (request()->isMethod('post')) {
            $params     = request()->all();
            $permission = Permission::create([
                'name' => $params['name'],
                'pid'  => $params['pid'],
                'url'  => $params['url'],
                'icon' => empty($params['icon']) ? 'layui-icon layui-icon-home' : $params['icon']
            ]);
            return response()->json($permission->id ? 1 : 0);
        }
        $permissions = $this->getAllPermission();
        return view('geauth::admin.permission.create', [
            'permissions' => $permissions,
            'pid'         => $id
        ]);
    }

    /**
     * 菜单图标
     * @adminMenu(
     *     'name'   => '菜单图标',
     *     'parent' => 'admin/permission',
     *     'router' => 'admin/permission/icon',
     *     'display'=> '',
     *     'hasView'=> false,
     *     'order'  => 50,
     *     'icon'   => '',
     * )
     */
    public function icon()
    {
        return view('geauth::admin.permission.icon');
    }

    /**
     * 编辑菜单
     * @adminMenu(
     *     'name'   => '编辑菜单',
     *     'parent' => 'admin/permission',
     *     'router' => 'admin/permission/edit',
     *     'display'=> '',
     *     'hasView'=> false,
     *     'order'  => 50,
     *     'icon'   => '',
     * )
     */
    public function edit(Request $request, $id=0)
    {
        if($request->isMethod('put')) {
            //编辑
            $params = $request->all();
            $permissions = Permission::where('id', $params['data']['id'])->update($params['data']);
            return response()->json($permissions);
        }
        $permission  = Permission::find($id);
        $permissions = $this->getAllPermission();
        return view('geauth::admin.permission.edit', [
            'permission'  => $permission,
            'permissions' => $permissions
        ]);
    }

    /**
     * 更新菜单
     * @adminMenu(
     *     'name'   => '更新菜单',
     *     'parent' => 'admin/permission',
     *     'router' => 'admin/permission/update',
     *     'display'=> '',
     *     'hasView'=> false,
     *     'order'  => 50,
     *     'icon'   => '',
     * )
     */
    public function update(Request $request, permission $permission)
    {
        $params      = $request->all();
        $permissions = $permission::where('id', $params['data']['id'])->update($params['data']);
        return response()->json($permissions);
    }


    public function destroy(Request $request)
    {
        //
        $isExists = Permission::where('pid', request()->input('id'))->exists();
        if (!$isExists) {
            $resp = Permission::destroy(request()->input('id', 0)) ? ['code' => 1, 'msg' => '操作成功'] : ['code' => 0, 'msg' => '操作异常'];
            return response()->json($resp);
        }
        return response()->json(['code' => 0, 'msg' => '请先删除下属权限菜单']);
    }

    /**
     * 导入菜单
     * @adminMenu(
     *     'name'   => '导入菜单',
     *     'parent' => 'admin/permission',
     *     'router' => 'admin/permission/import',
     *     'display'=> '',
     *     'hasView'=> false,
     *     'order'  => 50,
     *     'icon'   => '',
     * )
     */
    public function import()
    {
        $appPath = app_path() . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'admin';
        /*$apps = cmf_scan_dir(APP_PATH . '*', GLOB_ONLYDIR);

        array_push($apps, 'admin', 'user');

        $apps = array_values(array_unique($apps));

        $app = $this->request->param('app', '');
        if (empty($app)) {
            $app = $apps[0];
        }

        if (!in_array($app, $apps)) {
            $this->error('应用' . $app . '不存在!');
        }*/
        $newMenus = $this::importMenus($appPath);
        return response()->json($newMenus);

        /*$newMenus  = MenuLogic::importMenus($appPath);
        $index     = array_search($app, $apps);
        $nextIndex = $index + 1;
        $nextIndex = $nextIndex >= count($apps) ? 0 : $nextIndex;
        if ($nextIndex) {
            $this->assign("next_app", $apps[$nextIndex]);
        }
        $this->assign("app", $app);
        $this->assign("new_menus", $newMenus);

        Cache::clear('admin_menus');*/// 删除后台菜单缓存*/
        return response()->json('导入成功');
    }

    /**
     * @notes:获取含有顶部菜单的所有权限列表
     * @notes:用于权限创建、编辑展示
     * @return
     */
    private function getAllPermission()
    {
        $lists = Permission::select('id', 'pid', 'name', 'url', 'icon')->get()->toArray();
        //增加顶部菜单
        return permission::arr2table(array_merge($lists, [['id' => '0', 'pid' => '-1', 'url' => '#', 'name' => '顶部菜单']]));
    }
}
