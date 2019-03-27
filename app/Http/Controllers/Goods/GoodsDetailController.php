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

        $key = 'set:goods_click:'.$id;
        Redis::zadd($key,time(),time());


        $info = GoodsModel::where($where)->first();
        $info['click'] = Redis::zCard($key);
        return $info;
    }


    public function addCart(Request $request)
    {
        $where = [
            'goods_id'  =>  $request->input('goods_id'),
            'uid'       =>  $request->input('uid'),
        ];
        $info = CartModel::where($where)->first();
        if($info){ //修改
            $data = [
                'num'   =>  $info->num+$request->input('num')
            ];
            $rs = CartModel::where($where)->update($data);
        }else{//添加
            $data = [
                'goods_id'  =>  $request->input('goods_id'),
                'num'       =>  $request->input('num'),
                'add_time'  =>  time(),
                'uid'       =>  $request->input('uid'),
                'session_token' =>  Redis::hget('str:u:token:'.$request->input('uid'),'app')
            ];
            $rs = CartModel::insertGetId($data);
        }

        if($rs){
            $response = [
                'error' =>  0,
                'msg'   =>  '加入购物车成功'
            ];
        }else{
            $response = [
                'error' =>  500005,
                'msg'   =>  '加入购物车失败'
            ];
        }
        return $response;
    }

    public function goodsFav(Request $request)
    {
        $uid = $request->input('uid');
        //$uid = 1;
        $goods_id = $request->input('goods_id');
        //$goods_id = 11;
        $key = 'sets:goods_fav:'.$uid;
        $rs = Redis::zrange($key,0,-1);
        foreach($rs as $k=>$v){
            if($v==$goods_id){
                return [
                    'error' =>  522200,
                    'msg'   =>  '请勿重复收藏'
                ];
            }
        }
        $rs = Redis::zAdd($key,time(),$goods_id);
        if($rs){
            return [
                'error' =>  0,
                'msg'   =>  '已收藏'
            ];
        }else{
            return [
                'error' =>  522201,
                'msg'   =>  '收藏失败'
            ];
        }

    }
}
