<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/6
 * Time: 16:35
 */

namespace app\web\model;

use app\common\model\web\PurchaseRejectDetails as PurchaseRejectDetailsModel;
class PurchaseRejectDetails extends PurchaseRejectDetailsModel
{
    public function add($data)
    {
        return $this->insert([
            'orders_id' => $data['orders_id'],
            'orders_code' => $data['orders_code'],
            'goods_code' => $data['goods_code'],
            'goods_status' => $data['goods_status'],
            'goods_id'=>$data['goods_id'],
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

    public function saveData($data)
    {
        return $this->save([
            'orders_code' => $data['orders_code'],
            'orders_id' => $data['orders_id'],
            'goods_code' => $data['goods_code'],
            'color_id' => $data['color_id'],
            'size_id' => $data['size_id'],
            'goods_number' => $data['goods_number'],
            'goods_price' => $data['goods_price'],
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