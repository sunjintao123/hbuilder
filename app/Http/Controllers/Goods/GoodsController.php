<?php
  namespace App\Http\Controllers\Goods;
  use App\Model\GoodsModel;
  class GoodsController {
      public  function  goodsList(){
          $goodsInfo=GoodsModel::get();
          $response=[
              'list'=>$goodsInfo
          ];
          return $response;
      }
  }
