<?php

namespace Bennent\Geauth;

use Bennent\Geauth\Console\GeauthCommand;
use Illuminate\Support\ServiceProvider;

class GeauthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        // register our controller
        $this->app->make('Bennent\Geauth\Controllers\HelloWorldController');
        $this->app->make('Bennent\Geauth\Controllers\AdminBaseController');
        $this->app->make('Bennent\Geauth\Controllers\LoginController');
        $this->app->make('Bennent\Geauth\Controllers\IndexController');
        $this->app->make('Bennent\Geauth\Controllers\PermissionController');
        //注册视图
        $this->loadViewsFrom(__DIR__.'/views', 'geauth');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // 注册辅助函数
        require_once(__DIR__ . '/helpers.php');

        // 注册命令
        if ($this->app->runningInConsole()) {
            $this->commands([
                GeauthCommand::class
            ]);
        }
        //注册路由
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        //数据迁移
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        //注册发布文件
        $this->registerPublishes();

    }

    /**
     * @notes:发布资源
     * @user: Bennent_G
     */
    protected function registerPublishes()
    {
        $this->publishes([
            //发布配置文件
            __DIR__ . DIRECTORY_SEPARATOR . 'config' => config_path(),
            //发布静态文件
            __DIR__ . DIRECTORY_SEPARATOR . 'public' => public_path('geauth'),
            //发布辅助函数
            __DIR__ . DIRECTORY_SEPARATOR . 'helpers.php' => app_path('helpers.php'),

        ]);
    }
}
