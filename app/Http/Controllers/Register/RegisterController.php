<?php

namespace App\Http\Controllers\Register;
use foo\bar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;
use App\Model\UserModel;
use GuzzleHttp;

class RegisterController extends Controller
{
        public function register(Request $request){
//            echo "<pre>";print_r($_POST);echo "</pre>";
//            echo 1;die;
            $name=$request->input('uname');
//            echo $name;die;
            if(empty($name)){
               return [
                   'error'=>40001,
                   'msg'=>'必填项不能为空'
               ];
            }
            $pwd1=$request->input('u_pwd');
            if(empty($pwd1)){
                return [
                    'error'=>40001,
                    'msg'=>'必填项不能为空'
                ];
            }
            $pwd2=$request->input('u_pwd2');
            if(empty($pwd2)){
                return [
                    'error'=>40001,
                    'msg'=>'必填项不能为空'
                ];
            }
            if($pwd1!==$pwd2){
                return [
                    'error'=>40002,
                    'msg'=>'密码不一致，请重新输入'
                ];
            };
            $tel=$request->input('u_tel');
            if(empty($tel)){
                return [
                    'error'=>40001,
                    'msg'=>'必填项不能为空'
                ];
            }
            $email=$request->input('u_email');
            if(empty($email)){
                return [
                    'error'=>40001,
                    'msg'=>'必填项不能为空'
                ];
            }
            $res=UserModel::where(['name'=>$name])->first();
            if($res){
                return [
                    'error'=>40003,
                    'msg'=>'账号已存在'
                ];
            }
            $res2=UserModel::where(['tel'=>$tel])->first();
            if($res2){
                return [
                    'error'=>40004,
                    'msg'=>'手机号已存在'
                ];
            }
            $res3=UserModel::where(['email'=>$email])->first();
            if($res3){
                return [
                    'error'=>40005,
                    'msg'=>'邮箱已存在'
                ];
            }
            $pwd=password_hash($pwd1,PASSWORD_BCRYPT);
//	    echo $pwd;die;
//	    $pwd=password_verify($pwd1,'$2y$10$TGftIAn6wDc.mBF1Z0Mh8e8mxskkKbsOh8GCDnohgdhE2J/vujlCC');
//	    var_dump($pwd);die;
            //echo __METHOD__;
            //echo '<pre>';print_r($_POST);echo '</pre>';
            $data=[
                'name'=>$name,
                'password'=>$pwd,
                'tel'=>$tel,
                'email'=>$email,
                'reg_time'=>time(),
            ];
            $uid=UserModel::insertGetId($data);
            //var_dump($uid);
            if($uid){
                $token = substr(md5(time()) . mt_rand(1, 9999), 10, 10);
                $redis_key_token='str:u:token:'.$uid;
                Redis::hset($redis_key_token,'app',$token);
                return [
                    'error'=>0,
                    'msg'=>'注册成功',
                    'token'=>$token,
                    'user'=>$name,
                    'uid'=>$res->uid,
                    'email'=>$email,
                    'tel'=>$tel,
                ];

            }else{
                return [
                    'error'=>50002,
                    'msg'=>'注册失败'
                ];
            }
      }

}