<?php

namespace App\Http\Controllers\Home;

use App\Model\UserModel;
use foo\bar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;


class HomeController extends Controller
{
    public function homePage(Request $request)
    {
        //查看用户id
        $u_id = $request->input('u_id');
        //登录id
        $uid = $request->input('uid');

        if($uid == $u_id){
            return [
                'error' =>  66667,
                'msg'   =>  '本人查看本人空间',
            ];
        }

        //u_id 信息
        $uu_info = UserModel::where(['uid'=>$u_id])->first();

        //本人好友id
        $key = 'set:firend:'.$uid;
        $uu_id = Redis::zrange($key,0,-1);
        //查看用户好友id
        $u_key = 'set:firend:'.$u_id;
        $look_uid = Redis::zrange($u_key,0,-1);
        if(empty($uu_id) || empty($look_uid)){

            $common_info = [
                'error'     =>  66668,
                'msg'       =>  '您与改用户没有共同好友'
            ];
        }else{
            foreach($uu_id as $v){
                foreach($look_uid as $value){
                    if($v == $value){
                        $data = UserModel::where(['uid'=>$v])->first();
                        $common_info[] = $data;
                    }
                }
            }
        }
        $info = [
            'u_info'    =>  $uu_info,
            'common'    =>  $common_info
        ];
        return $info;

    }

    public function addFirend(Request $request)
    {
        //查看用户id
        $u_id = $request->input('u_id');
        //登录id
        $uid = $request->input('uid');

        $key = 'set:firend:'.$uid;

        $rs=Redis::zadd($key,time(),$u_id);
        if($rs){
            $response = [
                'error' =>  0,
                'msg'   =>  '添加好友成功'
            ];
        }else{
            $response = [
                'error' =>  66668,
                'msg'   =>  '您已添加他为好友,请勿重复添加'
            ];
        }
        return $response;
    }
}