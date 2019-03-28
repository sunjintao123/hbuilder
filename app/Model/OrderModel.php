<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    //
    public $table = 'p_order';
    public $timestamps = false;

    /**
     * 生成订单号
     */
    public static function generateOrderSN()
    {
        return time();
    }
}
