<?php


namespace app\web\model;

use app\common\model\web\TransferOrders as TransferOrdersModel;
use app\common\model\web\TransferOrdersDetails as TransferOrdersDetailsModel;
class TransferOrders extends TransferOrdersModel
{
    //关联调拨单详情
    public function details(){
        return $this->hasMany(TransferOrdersDetailsModel::class,"orders_id","orders_id");
    }
}