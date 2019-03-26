<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use App\Model\CartModel;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class GoodsDetailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function GoodsDetail($id)
    {
        $where = [
            'goods_id'  =>  $id,
        ];

        $info = GoodsModel::where($where)->first();
        return $info;
    }


    public function addCart(Request $request)
    {
        $data = [
            'goods_id'  =>  $request->input('goods_id'),
            'num'       =>  $request->input('num'),
            'add_time'  =>  time(),
            'uid'       =>  $request->input('uid'),
            'session_token' =>  Redis::hget('str:u:token').$request->input('uid')
        ];
        $id = CartModel::insertGetId($data);
        if($id){
            $response = [
                'error' =>  0,
                'msg'   =>  '添加成功'
            ];
        }else{
            $response = [
                'error' =>  500005,
                'msg'   =>  '添加失败'
            ];
        }
        return $response;
    }
}
