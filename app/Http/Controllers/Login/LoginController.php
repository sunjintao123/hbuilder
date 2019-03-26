<?php

namespace App\Http\Controllers\Login;
use foo\bar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp;

class LoginController extends Controller
{
    public function login(Request $request){
//        echo "<pre>";print_r($_POST);echo "</pre>";die;
        $uname=$request->input('uname');
        $pwd=$request->input('pwd');
//        echo $uname;echo "<br>";
//        echo $pwd;die;
        $where = [
            'name' =>  $uname,
        ];
        if(empty($uname)|| empty($pwd)){
            $response=[
                'error'=>400,
                'msg'=>'账号或密码不能为空'
            ];
        }
        $res = UserModel::where($where)->first();
        if ($res) {
            if (password_verify($pwd, $res->password)){
                $token = substr(md5(time()) . mt_rand(1, 9999), 10, 10);
                setcookie('uid', $res->uid, time() + 86400, '/', 'lixiaonitongxue.top', false, true);
                setcookie('token', $token, time() + 86400, '/', 'lixiaonitongxue.top', false, true);
//                $request->session()->put('u_token', $token);
//                $request->session()->put('uid', $res->u_id);
//                echo $token;die;
                $redis_key_token='str:u:token:'.$res->uid;
                Redis::del($redis_key_token);
                Redis::hset($redis_key_token,'android',$token);
                $response=[
                    'error'=>0,
                    'msg'=>'登录成功',
                    'token'=>$token,
                    'user'=>$uname,
                    'uid'=>$res->uid
                ];
            } else {
                $response=[
                    'error'=>500,
                    'msg'=>'登录失败'
                ];
            }
        }
        return json_encode($response);
    }
    public function center(Request $request){
        $token=$_POST['token'];
        $uid=$_POST['uid'];
        if(empty($token)|| empty($uid)){
            $response=[
                'errno'=>5001,
                'msg'=>'请先登录'
            ];
        }
        $a_token=Redis::hget('android');
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