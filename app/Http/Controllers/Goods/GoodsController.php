<?php
  namespace App\Http\Controllers\Goods;
  use App\Model\GoodsModel;
  use Illuminate\Support\Facades\Redis;
  class GoodsController {
      public  function  goodsList(){
          $goodsInfo=GoodsModel::get();
          $key="h:goodsInfo";
          Redis::hSet($key,'goodsInfo',$goodsInfo);
          Redis::expire($key,83600);
          return $goodsInfo;
      }
  }
