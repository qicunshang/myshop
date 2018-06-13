<?php

Route::group(['middleware' => ['auth:admin', 'web']], function ($router) {
    $router->get('/', ['uses' => 'AdminController@index','as' => 'admin.index']);

    $router->resource('index', 'IndexController');

    //目录
    $router->resource('menus', 'MenuController');

    //后台用户
    $router->get('adminuser/ajaxIndex',['uses'=>'AdminUserController@ajaxIndex','as'=>'admin.adminuser.ajaxIndex']);
    $router->resource('adminuser', 'AdminUserController');

    //权限管理
    $router->get('permission/ajaxIndex',['uses'=>'PermissionController@ajaxIndex','as'=>'admin.permission.ajaxIndex']);
    $router->resource('permission', 'PermissionController');

    //角色管理
    $router->get('role/ajaxIndex',['uses'=>'RoleController@ajaxIndex','as'=>'admin.role.ajaxIndex']);
    $router->resource('role', 'RoleController');

    //公告管理

//    $router->resource('notice', 'NoticeController');
    $router->get('notice/list',['uses'=>'NoticeController@index','as'=>'admin.notice.index']);
    $router->get('notice/create',['uses'=>'NoticeController@create','as'=>'admin.notice.create']);
    $router->get('notice/{id}',['uses'=>'NoticeController@edit','as'=>'admin.notice.edit']);
    $router->post('notice/save',['uses'=>'NoticeController@save','as'=>'admin.notice.save']);
    $router->get('notice/del/{id}',['uses'=>'NoticeController@del','as'=>'admin.notice.del']);

    //商品
    $router->get('goods/list',['uses'=>'GoodsController@index','as'=>'admin.goods.index']);
    $router->get('goods/create',['uses'=>'GoodsController@create','as'=>'admin.goods.create']);
    $router->get('goods/{id}',['uses'=>'GoodsController@edit','as'=>'admin.goods.edit']);
    $router->post('goods/save',['uses'=>'GoodsController@save','as'=>'admin.goods.save']);
    $router->get('goods/del/{id}',['uses'=>'GoodsController@del','as'=>'admin.goods.del']);
});

Route::get('login', ['uses' => 'AuthController@index','as' => 'admin.auth.index']);
Route::post('login', ['uses' => 'AuthController@login','as' => 'admin.auth.login']);

Route::get('logout', ['uses' => 'AuthController@logout','as' => 'admin.auth.logout']);

Route::get('register', ['uses' => 'AuthController@getRegister','as' => 'admin.auth.register']);
Route::post('register', ['uses' => 'AuthController@postRegister','as' => 'admin.auth.register']);

Route::get('password/reset/{token?}', ['uses' => 'PasswordController@showResetForm','as' => 'admin.password.reset']);
Route::post('password/reset', ['uses' => 'PasswordController@reset','as' => 'admin.password.reset']);
Route::post('password/email', ['uses' => 'PasswordController@sendResetLinkEmail','as' => 'admin.password.email']);
