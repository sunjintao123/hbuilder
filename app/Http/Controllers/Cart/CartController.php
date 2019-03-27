<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Model\CartModel;
use App\Model\GoodsModel;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function cartList(Request $request)
    {
        $uid = $request->input('uid');

        $where = [
            'uid'   =>  $uid
        ];
        if(empty(CartModel::where($where)->get()->toArray())){
            return [
                'error' =>  522222,
                'msg'   =>  '购物车为空'
            ];
        }
        $info = CartModel::where($where)->get()->toArray();

        foreach($info as $k=>$v){
            $where = [
                'goods_id'  =>  $v['goods_id']
            ];
            $goodsInfo = GoodsModel::where($where)->first()->toArray();
            //var_dump($v);
            $v['goods_name'] = $goodsInfo['goods_name'];
            $v['price'] = $goodsInfo['price'];
            $v['img'] = $goodsInfo['img'];
            $data[] = $v;
        }


        return $data;
    }
}
