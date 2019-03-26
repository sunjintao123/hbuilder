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

$router->get('user/login','User\IndexController@login');   //用户登录

$router->post('user/center','User\IndexController@uCenter');  //用户中心

$router->get('user/order','User\IndexController@order');  //用户中心

$router->post('test/encbc','User\IndexController@EnCbc');  //用户中心


$router->post('test/rsa','User\IndexController@rsa');


$router->post('test/api','Test\IndexController@apiTest');
$router->post('test/login','Test\IndexController@apiLogin');
$router->post('test/register','Test\IndexController@apiRegister');




$router->post('api/login','User\IndexController@apiLogin');

$router->post('api/center','User\IndexController@apiCenter');

$router->get('pay/alipay/return','Pay\IndexController@aliReturn');    //同步
$router->post('pay/alipay/notify','Pay\IndexController@notify');    //异步
$router->get('pay/alipay','Pay\IndexController@pay');    //订单






