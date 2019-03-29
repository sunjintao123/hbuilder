<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});



$router->post('login','Login\LoginController@login');//登录

$router->post('/changepwd','Login\ChangePwdController@changePwd');//修改密码

$router->post('center','Login\LoginController@center');//个人中心

$router->get('/goods/list',"Goods\GoodsController@goodsList"); //商品展示

$router->get('/goods/detail/{id}',"Goods\GoodsDetailController@GoodsDetail"); //商品详情

$router->post('/cart/add',"Goods\GoodsDetailController@addCart"); //加入购物车

$router->post('/order/list',"Order\OrderController@orderList");//订单展示
$router->post('/orderadd',"Order\OrderController@orderAdd");//添加订单
$router->get('/orderadd',"Order\OrderController@orderAdd");//添加订单


$router->post('/cart/list',"Cart\CartController@cartList");//购物车展示

$router->post('/goods/fav',"Goods\GoodsDetailController@addGoodsFav");//加入收藏
$router->post('/goods/zan',"Goods\GoodsDetailController@addGoodsZan");//点赞

$router->post('/goods/favlist',"Goods\GoodsDetailController@goodsFav");//收藏列表
$router->post('/goods/zanlist',"Goods\GoodsDetailController@goodsZan");//点赞列表

