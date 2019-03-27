<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Model\CartModel;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function cartList(Request $request)
    {
        $uid = $request->input('uid');

        $where = [
            'uid'   =>  $uid
        ];
        $info = CartModel::where($where)->get();

        return $info;
    }
}
