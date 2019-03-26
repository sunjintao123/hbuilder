<?php

namespace App\Http\Controllers;
use App\Model\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controllers
{
    public function login(Request $request){
        echo "<pre>";print_r($_POST);echo "</pre>";die;
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
            if (password_verify($pwd, $res->pwd)){
                $token = substr(md5(time()) . mt_rand(1, 9999), 10, 10);
                setcookie('uid', $res->u_id, time() + 86400, '/', 'wangby.cn', false, true);
                setcookie('token', $token, time() + 86400, '/', 'wangby.cn', false, true);
//                $request->session()->put('u_token', $token);
//                $request->session()->put('uid', $res->u_id);
//                echo $token;die;
                $redis_key_token='str:u:token:'.$res->u_id;
                Redis::del($redis_key_token);
                Redis::hset($redis_key_token,'android',$token);
                $response=[
                    'error'=>0,
                    'msg'=>'登录成功',
                    'token'=>$token,
                    'user'=>$uname,
                    'uid'=>$res->u_id
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
    public function quit(){
        setcookie('uid', null, time()-1, '/', 'wangby.cn', false, true);
        setcookie('token', null, time()-1, '/', 'wangby.cn', false, true);
        echo "退出成功";
        header('refresh:1;url=http://dzh.wangby.cn');
    }

}