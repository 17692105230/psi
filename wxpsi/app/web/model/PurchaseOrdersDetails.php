<?php


namespace app\web\model;

use app\common\model\web\PurchaseOrdersDetails as PurchaseOrdersDetailsModel;
class PurchaseOrdersDetails extends PurchaseOrdersDetailsModel
{
    public function add($data)
    {
        return $this->insert([
            'orders_id' => $data['orders_id'],
            'orders_code' => $data['orders_code'],
            'goods_code' => $data['goods_code'],
            'color_id' => $data['color_id'],
            'size_id' => $data['size_id'],
            'goods_number' => $data['goods_number'],
            'goods_price' => $data['goods_price'],
            'goods_tmoney' => $data['goods_tmoney'],
            'goods_discount' => $data['goods_discount'],
            'goods_daprice' => $data['goods_daprice'],
            'goods_tdamoney' => $data['goods_tdamoney'],
            'create_time' => $data['time_now'],
            'update_time' => $data['time_now'],
            'com_id' => $data['com_id'],
        ]);
    }
}