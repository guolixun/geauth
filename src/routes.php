<?php

use Illuminate\Support\Facades\Route;

// 测试demo
Route::get('admin/hello', 'Bennent\Geauth\Controllers\HelloWorldController@hello');
Route::get('hello/index', 'Bennent\Geauth\Controllers\HelloWorldController@index');

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {
    // 登录
    Route::match(['get', 'post'], '', 'Bennent\Geauth\Controllers\LoginController@login')->name('geauth.admin');
    Route::get('logout', 'Bennent\Geauth\Controllers\LoginController@logout')->name('geauth.admin.logout');
    Route::get('index', 'Bennent\Geauth\Controllers\IndexController@index')->name('geauth.admin.index');
    Route::get('console', 'Bennent\Geauth\Controllers\IndexController@console')->name('geauth.admin.console');
    Route::get('show1', 'Bennent\Geauth\Controllers\IndexController@console1')->name('geauth.admin.console1');

    // 菜单管理
    Route::prefix('permission')->group(function() {
       Route::match(['get', 'post'], '', 'Bennent\Geauth\Controllers\PermissionController@index')->name('geauth.admin.permission');
       Route::match(['get', 'post'], 'create/{id?}', 'Bennent\Geauth\Controllers\PermissionController@create')->name('geauth.admin.permission.create');
       Route::get('icon', 'Bennent\Geauth\Controllers\PermissionController@icon')->name('geauth.admin.permission.icon');
       Route::match(['get', 'post', 'put'],'edit/{id?}', 'Bennent\Geauth\Controllers\PermissionController@edit')->name('geauth.admin.permission.edit');
       Route::delete('', 'Bennent\Geauth\Controllers\PermissionController@destroy')->name('geauth.admin.permission.destroy');
    });

    // 角色管理
    Route::prefix('role')->group(function() {
       Route::match(['get', 'post'], '', 'Bennent\Geauth\Controllers\RoleController@index')->name('geauth.admin.role');
       Route::match(['get', 'post'], 'create', 'Bennent\Geauth\Controllers\RoleController@create')->name('geauth.admin.role.create');
       Route::match(['get', 'post', 'put'],'edit/{id?}', 'Bennent\Geauth\Controllers\RoleController@edit')->name('geauth.admin.role.edit');
       Route::match(['get', 'post'], 'read/{id?}', 'Bennent\Geauth\Controllers\RoleController@read')->name('geauth.admin.role.read');
       Route::post('auth', 'Bennent\Geauth\Controllers\RoleController@auth')->name('geauth.admin.role.auth');
       Route::delete('', 'Bennent\Geauth\Controllers\RoleController@destroy')->name('geauth.admin.role.destroy');
    });

    //管理员
    Route::prefix('manager')->group(function() {
        Route::match(['get', 'post'], '', 'Bennent\Geauth\Controllers\ManagerController@index')->name('geauth.admin.manager');
        Route::match(['get', 'post'], 'create', 'Bennent\Geauth\Controllers\ManagerController@create')->name('geauth.admin.manager.create');
        Route::post('avatar', 'Bennent\Geauth\Controllers\ManagerController@avatar')->name('geauth.admin.manager.avatar');
        Route::match(['get', 'post', 'put'],'edit/{id?}', 'Bennent\Geauth\Controllers\ManagerController@edit')->name('geauth.admin.manager.edit');
        Route::match(['get', 'post'], 'pass/{uid?}', 'Bennent\Geauth\Controllers\ManagerController@pass')->name('geauth.admin.manager.pass');
        Route::delete('', 'Bennent\Geauth\Controllers\ManagerController@destroy')->name('geauth.admin.manager.destroy');
    });
});

