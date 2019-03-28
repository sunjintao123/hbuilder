<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Model\CartModel;
use App\Model\GoodsModel;
use App\Model\OrderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class OrderController extends Controller
{
    public function orderAdd(Request $request){
        $uid = $request -> input('uid');
        //$uid =1;
        $cart_goods = CartModel::where(['uid'=> $uid])->get()->toArray();
        if(empty($cart_goods)){
            return [
                'error' => 40005,
                'msg'   => '购物车为空'
            ];
        }
        $order_amount = 0;

        foreach($cart_goods as $k=>$v){
            $goods_info =  GoodsModel::where(['goods_id' => $v['goods_id']])->first()->toArray();

            $goods_info['goods_num'] = $v['num'];
            $list[] = $goods_info;

            //计算订单总价
            $order_amount += $goods_info['price'] * $v['num'];

            $order_sn = OrderModel::generateOrderSN();

            $data = [
                'order_sn'  =>  $order_sn,
                'uid'   =>  $uid,
                'add_time'  =>  time(),
                'order_amount'   =>  $order_amount/100
            ];

            $oid = OrderModel::insertGetId($data);

            if(!$oid){
                $response = [
                    'error' => 40003,
                    'msg'   => '提交订单失败'
                ];
            }else{
                $response = [
                    'error' => 0,
                    'msg'   => '下单成功'
                ];
                CartModel::where(['uid'=>$uid])->delete();
            }
            return $response;
        }
    }

    public function orderList(Request $request){
        $uid = $request -> input('uid');
        $where = [
            'uid' => $uid
        ];
        $orderInfo=OrderModel::where($where)->get();

        foreach($orderInfo as $k=>$v){
            $v['add_time'] = date('Y-m-d H:i:s' , $v['add_time']);
            $info[] = $v;
        }
        return $info;
    }
}