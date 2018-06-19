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

    //订单
    $router->get('order/list',['uses'=>'OrderController@index','as'=>'admin.order.index']);
    $router->get('order/create',['uses'=>'OrderController@create','as'=>'admin.order.create']);
    $router->get('order/{id}',['uses'=>'OrderController@edit','as'=>'admin.order.edit']);
    $router->post('order/save',['uses'=>'OrderController@save','as'=>'admin.order.save']);
    $router->get('order/del/{id}',['uses'=>'OrderController@del','as'=>'admin.order.del']);

    //商品分类
    $router->get('category/list',['uses'=>'CategoryController@index','as'=>'admin.category.index']);
    $router->get('category/create',['uses'=>'CategoryController@create','as'=>'admin.category.create']);
    $router->get('category/{id}',['uses'=>'CategoryController@edit','as'=>'admin.category.edit']);
    $router->post('category/save',['uses'=>'CategoryController@save','as'=>'admin.category.save']);
    $router->get('category/del/{id}',['uses'=>'CategoryController@del','as'=>'admin.category.del']);

    //用户
    $router->get('users/list',['uses'=>'UsersController@index','as'=>'admin.users.index']);
    $router->get('users/create',['uses'=>'UsersController@create','as'=>'admin.users.create']);
    $router->get('users/{id}',['uses'=>'UsersController@edit','as'=>'admin.users.edit']);
    $router->post('users/save',['uses'=>'UsersController@save','as'=>'admin.users.save']);
    $router->get('users/del/{id}',['uses'=>'UsersController@del','as'=>'admin.users.del']);
});

Route::get('login', ['uses' => 'AuthController@index','as' => 'admin.auth.index']);
Route::post('login', ['uses' => 'AuthController@login','as' => 'admin.auth.login']);

Route::get('logout', ['uses' => 'AuthController@logout','as' => 'admin.auth.logout']);

Route::get('register', ['uses' => 'AuthController@getRegister','as' => 'admin.auth.register']);
Route::post('register', ['uses' => 'AuthController@postRegister','as' => 'admin.auth.register']);

Route::get('password/reset/{token?}', ['uses' => 'PasswordController@showResetForm','as' => 'admin.password.reset']);
Route::post('password/reset', ['uses' => 'PasswordController@reset','as' => 'admin.password.reset']);
Route::post('password/email', ['uses' => 'PasswordController@sendResetLinkEmail','as' => 'admin.password.email']);
