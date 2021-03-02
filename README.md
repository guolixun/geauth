
## About GE-AUTH

GE-AUTH是基于laravel7的RBAC权限的纯粹管理扩展包。

Install
- 首先确保安装好了 laravel,并且数据库连接设置正确
- 加载扩展
```
composer require bennent/geauth
```
- 发布资源
```
php artisan vendor:publish --provider="Bennent\Geauth\GeauthServiceProvider"
```
- 安装
```
php artisan geauth:install
```
- 安装完成后，目录结构如下：
```
app
├── Http
│   ├── Controllers
│   ├──    ├── Admin
│   ├──    ├──  ├── HomeController.php
│   ├──    ├──  ├── ...  //后台的主要逻辑可以全部写在这
routes
└── admin.php //后台的路由在这
... //其余的与laravel目录全部保持一致
```
- 配置
```
app\Providers\RouteServiceProvider.php 增加后台路由分类解析
public function map()
{
    ...
+    $this->mapAdminRoutes();
}
+++ 可根据实际情况自行调整即可
protected function mapAdminRoutes()
{
    Route::prefix('admin')
        ->namespace($this->namespace . '\Admin')
        ->group(base_path('routes/admin.php'));
}

app\Http\Kernel.php 增加路由中间件分组

protected $middlewareGroups = [
    ...
+++ 'admin' => [
+++    \Illuminate\Session\Middleware\StartSession::class
+++ ]
    ...
];
```

- 启动服务后，直接访问 http://localhost/admin, 用户名[admin] 密码[admin123]