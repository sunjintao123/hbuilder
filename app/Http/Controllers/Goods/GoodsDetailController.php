<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use App\Model\GoodsModel;

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

        $info = GoodsModel::where($where)->first();
        return $info;
    }
}
