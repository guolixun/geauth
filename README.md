
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
│   ├──    ├──  ├── ...  //this will backend business logic
routes
└── admin.php // this is backend router
... 
```
- Settings
```
app\Providers\RouteServiceProvider.php
public function map()
{
    ...
+    $this->mapAdminRoutes();
}
+++
protected function mapAdminRoutes()
{
    Route::prefix('admin')
        ->namespace($this->namespace . '\Admin')
        ->group(base_path('routes/admin.php'));
}

app\Http\Kernel.php

protected $middlewareGroups = [
    ...
+++  // the admin route middleware groups
+++ 'admin' => [
+++    \Illuminate\Session\Middleware\StartSession::class
+++ ]
    ...
];
```

- Start Service，access http://localhost/admin, user[admin] pass[admin123]