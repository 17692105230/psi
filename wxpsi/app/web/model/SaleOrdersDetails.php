<?php


namespace app\web\model;

use app\common\model\web\SaleOrdersDetails as SaleOrdersDetailsModel;
class SaleOrdersDetails extends SaleOrdersDetailsModel
{

    /**
     * Notes:新增插入
     * User:ccl
     * DateTime:2021/1/26 9:50
     * @param $data
     * @return int|string
     */
    public function addTransfer($data)
    {
        return $this->insert([
            'orders_id' => $data['orders_id'],
            'orders_code' => $data['orders_code'],
            'goods_code' => $data['goods_code'],
            'goods_id' => $data['goods_id'],
            'color_id' => $data['color_id'],
            'size_id' => $data['size_id'],
            'goods_number' => $data['goods_number'],
            'goods_price' => $data['goods_price'],
            'goods_tmoney' => $data['goods_tmoney'],
            'goods_status' => $data['goods_status'],
            'com_id' => $data['com_id'],
            'goods_tdamoney' =>  $data['goods_tmoney'],
            'goods_discount' => isset($data['goods_discount']) ? $data['goods_discount'] : 100,
            'goods_daprice' => $data['goods_tmoney'],
            'create_time' => strtotime($data['create_time']),
            'update_time' => strtotime($data['update_time'] )
        ]);
    }
}
