<?php
  namespace App\Http\Controllers\Crontab;
  use App\Model\GoodsModel;
  use App\Model\OrderModel;
  use Illuminate\Support\Facades\Redis;
  class CrontabController {


      //计划任务
      public function cronOrder()
      {
          $info = OrderModel::get();

          foreach($info as $k=>$v){
              if(time()-$v['add_time']>300 && $v['is_pay']==0){
                  OrderModel::where(['order_id'=>$v['order_id']])->update(['is_delete'=>1]);
              }
          };
          echo '时间:'.date('Y-m-d H:i:s');
      }
  }
