<?php

namespace App\Http\Controllers\Login;
use foo\bar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;
use App\Model\UserModel;
use GuzzleHttp;

class LoginController extends Controller
{
    public function login(Request $request){
//        echo "<pre>";print_r($_POST);echo "</pre>";die;
        $uname=$request->input('uname');
        $pwd=$request->input('pwd');
        if(substr_count($uname,'@')){
            $where=[
                'email'=>$uname
            ];
        }elseif(is_numeric($uname)&&strlen($uname)==11){
            $where=[
                'tel'=>$uname
            ];
        }else{
            $where=[
                'name'=>$uname
            ];
        }
        if(empty($uname)|| empty($pwd)){
            return [
                'error'=>40001,
                'msg'=>'账号或密码不能为空'
            ];
        }
        $res = UserModel::where($where)->first();
//        var_dump($res);die;
        if ($res) {
            if (password_verify($pwd, $res->password)){
                $token = substr(md5(time()) . mt_rand(1, 9999), 10, 10);
                $redis_key_token='str:u:token:'.$res->uid;
                Redis::del($redis_key_token);
                Redis::hset($redis_key_token,'app',$token);
                $response=[
                    'error'=>0,
                    'msg'=>'登录成功',
                    'token'=>$token,
                    'user'=>$uname,
                    'uid'=>$res->uid
                ];
            } else {
                $response=[
                    'error'=>50002,
                    'msg'=>'登录失败'
                ];
            }
            return $response;
        }

    }
    public function center(Request $request){
//        echo 1;
        $token=$request->input('token');
        $uid=$request->input('uid');
        if(empty($token) || empty($uid)){
            $response=[
                'errno'=>5001,
                'msg'=>'请先登录'
            ];
            return $response;
        }
        $a_token=Redis::hget('str:u:token:'.$uid,'app');
        if($token==$a_token){
            $response=[
                'errno'=>0,
                'msg'=>'ok'
            ];
        }else{
            $response=[
                'errno'=>5002,
                'msg'=>'非法登录'
            ];
        }
        return $response;
    }

}