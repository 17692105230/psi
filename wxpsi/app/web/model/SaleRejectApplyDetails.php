<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/15
 * Time: 11:59
 */

namespace app\web\model;
use app\common\model\web\SaleRejectApplyDetails as SaleRejectApplyDetailsModel;

class SaleRejectApplyDetails extends  SaleRejectApplyDetailsModel
{
    public function add($data){
        return $this->insert([
            'orders_id' => $data['orders_id'],
            'orders_code' => $data['orders_code'],
            'goods_code' => $data['goods_code'],
            'goods_id' => $data['goods_id'],
            'color_id' => $data['color_id'],
            'goods_status' => $data['goods_status'],
            'size_id' => $data['size_id'],
            'goods_number' => $data['goods_number'],
            'goods_price' => $data['goods_price'],
            'goods_tmoney' => $data['goods_tmoney'],
            'com_id' => $data['com_id'],
            'create_time' => $data['time_now'],
            'update_time' => $data['time_now']
        ]);
    }

    public function saveData($data){
        return $this->save([
            'orders_id' => $data['orders_id'],
            'orders_code' => $data['orders_code'],
            'goods_code' => $data['goods_code'],
            'color_id' => $data['color_id'],
            'size_id' => $data['size_id'],
            'goods_number' => $data['goods_number'],
            'goods_price' => $data['goods_price'],
            'goods_tmoney' => $data['goods_tmoney'],
            'com_id' => $data['com_id'],
            'goods_status' => isset($data['goods_status']) ? $data['goods_status'] : 0,
            'lock_version' => isset($data['lock_version']) ? $data['lock_version'] : 0,
        ]);
    }
}