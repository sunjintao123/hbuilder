<?php

namespace App\Http\Controllers\Login;
use foo\bar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;
use App\Model\UserModel;
use GuzzleHttp;

class ChangePwdController extends Controller
{
    public function changePwd(Request $request){
        $uid = $request->input('uid');
        $pwd = $request->input('pwd');
        $npwd = $request->input('npwd');
        $npwdd = $request->input('npwdd');

        if(empty($pwd)){
            return [
                'error'=>400,
                'msg'=>'当前密码不能为空'
            ];
        }

        $res = UserModel::where(['uid' => $uid])->first();

        if(!password_verify($pwd, $res->password)){
            return [
                'error'=>400,
                'msg'=>'当前密码错误'
            ];
        }
        if(empty($npwd)){
            return [
                'error'=>400,
                'msg'=>'新密码不能为空'
            ];
        }
        if(empty($npwdd)){
            return [
                'error'=>400,
                'msg'=>'确认新密码不能为空'
            ];
        }

        if($npwd != $npwdd){
            return [
                'error'=>400,
                'msg'=>'两次输入的密码不一致'
            ];
        }

        $res1 = UserModel::where(['uid' => $uid])->update(['password' => password_hash($npwd,PASSWORD_BCRYPT)]);
        if($res1){
            return [
                'error'=>0,
                'msg'=>'修改成功，请重新登陆'
            ];
        }else{
            return [
                'error'=>400,
                'msg'=>'修改失败'
            ];
        }
    }
}