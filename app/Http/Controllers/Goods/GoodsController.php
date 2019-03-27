<?php
  namespace App\Http\Controllers\Goods;
  use App\Model\GoodsModel;
  use Illuminate\Support\Facades\Redis;
  class GoodsController {
      public  function  goodsList(){
          $key="h:goodsInfo";
          $goodsInfo=Redis::hGet('goodsInfo',$key);
          if(empty($goodsInfo)){
              $goodsInfo=GoodsModel::get();
              Redis::hSet($key,'goodsInfo',$goodsInfo);
              Redis::expire($key,83600);
          }
          return $goodsInfo;
      }
  }
