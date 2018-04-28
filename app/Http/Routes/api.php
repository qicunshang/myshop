<?php


Route::group([['prefix' => 'api', 'namespace' => 'api'], 'middleware' => ['web']], function($router) {
    $router->any('/', ['uses' => 'AdminController@index','as' => 'admin.index']);

    $router->any('notice/list', ['uses' => 'NoticeController@noticeList', 'as' => 'notice.noticeList']);
    $router->any('notice/detail', ['uses' => 'NoticeController@detail', 'as' => 'notice.detail']);


    $router->any('category/list', ['uses' => 'CategoryController@categoryList', 'as' => 'category.categoryList']);


    $router->any('goods/list', ['uses' => 'GoodsController@goodsList', 'as' => 'goods.goodsList']);
    $router->any('goods/detail', ['uses' => 'GoodsController@detail', 'as' => 'goods.detail']);

    //登录
    $router->any('user/login', ['uses' => 'UserController@login', 'as' => 'user.login']);
});

/*需要带token验证*/
Route::group(['middleware' => ['web', 'CheckToken']], function ($router) {
    //订单
    $router->any('order/create', ['uses' => 'OrderController@create', 'as' => 'order.create']);
    $router->any('order/list', ['uses' => 'OrderController@orderList', 'as' => 'order.orderList']);
    $router->any('order/detail', ['uses' => 'OrderController@detail', 'as' => 'order.detail']);

    //收货地址
    $router->any('address/list', ['uses' => 'AddressController@addressList', 'as' => 'address.list']);
    $router->any('address/detail', ['uses' => 'AddressController@detail', 'as' => 'address.detail']);
    $router->any('address/create', ['uses' => 'AddressController@create', 'as' => 'address.create']);
    $router->any('address/update', ['uses' => 'AddressController@update', 'as' => 'address.update']);
    $router->any('address/delete', ['uses' => 'AddressController@del', 'as' => 'address.delete']);
});