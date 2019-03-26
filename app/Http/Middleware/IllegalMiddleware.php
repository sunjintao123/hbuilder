<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class IllegalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //print_r($_SERVER);
        $request_uri = $_SERVER['REQUEST_URI'];

        $hash_uri = substr(md5($request_uri),0,10);

        //echo $hash_uri;

        $client_ip = $_SERVER['REMOTE_ADDR'];

        $redis_key = 'str:url:' . $hash_uri . ',ip:' . $client_ip;
        //$request->headers->set('Accept', 'application/json');
        //echo $redis_key;
        $num = Redis::incr($redis_key);
        Redis::expire($redis_key,60);

        //echo $num;
        if($num > 5){
            $key = 'set:illegal_ip';
            Redis::sAdd($key,$client_ip);

            Redis::expire($redis_key,600);
            $response = [
                'errno'   =>  50002,
                'msg'       =>  'invalid request'
            ];
            return json_encode($response);
        }

        return $next($request);
    }
}