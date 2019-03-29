<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use App\Model\CartModel;
use App\Model\GoodsModel;
use App\Model\UserModel;
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

    public function GoodsDetail(Request $request)
    {
        $goods_id = $request->input('goods_id');
        $uid = $request->input('uid');
        $where = [
            'goods_id'  =>  $goods_id,
        ];

        $key = 'set:goods_click:'.$goods_id;
        Redis::zadd($key,time(),time());

        //添加浏览过的用户
        $u_key = 'sets:goods_user'.$goods_id;
        Redis::zadd($u_key,time(),$uid);

        $goods_key = 'str:goods_detail:'.$goods_id;
        $data = Redis::get($goods_key);
        if(empty($data)){
            $info = GoodsModel::where($where)->first();
            Redis::set($goods_key,json_encode($info));
            Redis::expire($goods_key,60*60*3);

        }else{
            $info = json_decode($data,true);
        }
        $info['click'] = Redis::zCard($key);
        $info['liulan'] =   [
            'error'     =>  66666,
            'msg'       =>  '无人浏览'
        ];
        if($info){
            //展示浏览过的用户
            $u_id = Redis::zrange($u_key,0,-1);
            foreach($u_id as $k=>$v){
                $u_data = UserModel::where(['uid'=>$v])->first();
                $u_info[] = $u_data;
            }
            $info['liulan'] = $u_info;
        }

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

    public function addGoodsFav(Request $request)
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
                'msg'   =>  '收藏成功'
            ];
        }else{
            return [
                'error' =>  522201,
                'msg'   =>  '收藏失败'
            ];
        }

    }

    public function goodsFav(Request $request)
    {
        $uid = $request->input('uid');
        //$uid = 11;
        $key = 'sets:goods_fav:'.$uid;
        $goods_id = Redis::zrange($key,0,-1);
        if(empty($goods_id)){
            return [
                'error' =>  46646,
                'msg'   =>  '您还没有收藏的商品'
            ];
        }
        foreach($goods_id as $v){
            $data = GoodsModel::where(['goods_id'=>$v])->first()->toArray();
            $info[] = $data;
        }

        return $info;
    }

    public function goodsZan(Request $request)
    {
        $uid = $request->input('uid');
        //$uid = 11;
        $key = 'sets:goods_zan:'.$uid;
        $goods_id = Redis::zrange($key,0,-1);
        if(empty($goods_id)){
            return [
                'error' =>  46646,
                'msg'   =>  '您还没有点赞的商品'
            ];
        }
        foreach($goods_id as $v){
            $data = GoodsModel::where(['goods_id'=>$v])->first()->toArray();
            $info[] = $data;
        }

        return $info;
    }

    public function addGoodsZan(Request $request)
    {
        $uid = $request->input('uid');
        //$uid = 1;
        $goods_id = $request->input('goods_id');
        //$goods_id = 11;
        $key = 'sets:goods_zan:'.$uid;
        $rs = Redis::zrange($key,0,-1);
        foreach($rs as $k=>$v){
            if($v==$goods_id){
                return [
                    'error' =>  522200,
                    'msg'   =>  '请勿重复点赞'
                ];
            }
        }
        $rs = Redis::zAdd($key,time(),$goods_id);
        if($rs){
            return [
                'error' =>  0,
                'msg'   =>  '点赞成功'
            ];
        }else{
            return [
                'error' =>  522201,
                'msg'   =>  '点赞失败'
            ];
        }

    }
}
