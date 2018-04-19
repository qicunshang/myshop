<?php


Route::group([['prefix' => 'api', 'namespace' => 'api'], 'middleware' => ['web']], function($router) {
    $router->any('/', ['uses' => 'AdminController@index','as' => 'admin.index']);

    $router->any('notice/list', ['uses' => 'NoticeController@noticeList', 'as' => 'notice.noticeList']);


    $router->any('category/list', ['uses' => 'CategoryController@categoryList', 'as' => 'category.categoryList']);


    $router->any('goods/list', ['uses' => 'GoodsController@GoodsList', 'as' => 'goods.goodsList']);
    $router->any('goods/detail', ['uses' => 'GoodsController@detail', 'as' => 'goods.detail']);
});