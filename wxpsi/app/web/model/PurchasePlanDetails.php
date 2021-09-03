<?php


namespace app\web\model;

use app\common\model\web\PurchasePlanDetails as PurchasePlanDetailsModel;

class PurchasePlanDetails extends PurchasePlanDetailsModel
{
    public function add($data)
    {
        return $this->insert([
            'orders_id' => $data['orders_id'],
            'orders_code' => $data['orders_code'],
            'goods_code' => $data['goods_code'],
            'goods_status' => $data['goods_status'],
            'color_id' => $data['color_id'],
            'goods_id'=>$data['goods_id'],
            'size_id' => $data['size_id'],
            'goods_number' => $data['goods_number'],
            'goods_discount' => $data['goods_discount'],
            'goods_rprice' => $data['goods_rprice'], // 单价
            'goods_tmoney' => $data['goods_tmoney'], // 合计金额
            'goods_daprice' => $data['goods_daprice'], // 折扣价
            'goods_tdamoney' => $data['goods_tdamoney'],// 折扣金额
            'create_time' => $data['time_now'],
            'update_time' => $data['time_now'],
            'com_id' => $data['com_id'],
        ]);
    }

    public function saveData($data)
    {
        return $this->save([
            'orders_code' => $data['orders_code'],
            'orders_id' => $data['orders_id'],
            'goods_code' => $data['goods_code'],
            'color_id' => $data['color_id'],
            'size_id' => $data['size_id'],
            'goods_number' => $data['goods_number'],
            'goods_rprice' => $data['goods_rprice'],
            'goods_tmoney' => $data['goods_tmoney'],
            'goods_discount' => $data['goods_discount'],
            'goods_daprice' => $data['goods_daprice'],
            'goods_tdamoney' => $data['goods_tdamoney'],
            'goods_status' => isset($data['goods_status']) ? $data['goods_status'] : 0,
            'lock_version' => isset($data['lock_version']) ? $data['lock_version'] : 0,
            'com_id' => $data['com_id'],
        ]);
    }
}