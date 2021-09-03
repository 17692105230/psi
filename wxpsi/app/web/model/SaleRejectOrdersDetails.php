<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/15
 * Time: 14:21
 */

namespace app\web\model;
use app\common\model\web\SaleRejectOrdersDetails as SaleRejectOrdersDetailsModel;
class SaleRejectOrdersDetails extends SaleRejectOrdersDetailsModel
{
    public function addSro($data) {
        return $this->insert([
            'orders_id' => $data['orders_id'],
            'orders_code' => $data['orders_code'],
            'goods_code' => $data['goods_code'],
            'goods_type' => $data['goods_type'],
            'goods_id' => $data['goods_id'],
            'color_id' => $data['color_id'],
            'size_id' => $data['size_id'],
            'goods_number' => $data['goods_number'],
            'goods_price' => $data['goods_price'],
            'goods_tmoney' => $data['goods_tmoney'],
            'goods_status' => $data['goods_status'],
            'com_id' => $data['com_id'],
            'goods_discount' => isset($data['goods_discount']) ? $data['goods_discount'] : 100,
            'goods_daprice' => isset($data['goods_daprice']) ? $data['goods_daprice'] : $data['goods_price'],
            'goods_tdamoney' => isset($data['goods_tdamoney']) ? $data['goods_tdamoney'] : $data['goods_tmoney'],
            'create_time' => isset($data['create_time']) ? strtotime($data['create_time']) : $data['time_now'],
            'update_time' => isset($data['update_time']) ? strtotime($data['update_time'] ): $data['time_now']
        ]);
    }

    public function savaData($data) {
        return $this->save([
            'orders_id' => $data['orders_id'],
            'orders_code' => $data['orders_code'],
            'goods_type' => $data['goods_type'],
            'goods_code' => $data['goods_code'],
            'goods_id' => $data['goods_id'],
            'color_id' => $data['color_id'],
            'size_id' => $data['size_id'],
            'goods_number' => $data['goods_number'],
            'goods_price' => $data['goods_price'],
            'goods_tmoney' => $data['goods_tmoney'],
            'goods_status' => $data['goods_status'],
            'com_id' => $data['com_id'],
            'goods_discount' => isset($data['goods_discount']) ? $data['goods_discount'] : 100,
            'goods_daprice' => isset($data['goods_daprice']) ? $data['goods_daprice'] : $data['goods_price'],
            'goods_tdamoney' => isset($data['goods_tdamoney']) ? $data['goods_tdamoney'] :  $data['goods_tmoney'],
            'lock_version' => isset($data['lock_version']) ? $data['lock_version'] : 0,
        ]);
    }


}