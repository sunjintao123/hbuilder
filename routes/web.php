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
$router->post('center','Login\LoginController@center');//个人中心
$router->get('/goods/list',"Goods\GoodsController@goodsList");
$router->get('/goods/detail/{id}',"Goods\GoodsDetailController@GoodsDetail");
