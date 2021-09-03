<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/5
 * Time: 14:20
 */

namespace app\web\model;

use app\common\model\web\GoodsDetails as GoodsDetailsModel;

class GoodsDetails extends GoodsDetailsModel
{
    public function deleteGoodsDetails($goods_id)
    {
        return $this->where($goods_id)->delete();
    }

    public function findGoods($where)
    {
        return $this->where($where)->find();
    }

    public function saveDetails($rows, $com_id, $goods_code, $goods_id)
    {
        $item = [
            'goods_code' => $goods_code,
            'goods_id' => $goods_id,
            'color_id' => $rows['color_id'],
            'size_id' => $rows['size_id'],
            'history_number' => 0,
            'goods_scode' => $rows['goods_scode'],
            'goods_sbarcode' => $rows['goods_sbarcode'],
            'lock_version' => 0,
            'com_id' => $com_id,
            'create_time' => time(),
            'update_time' => time()
        ];
        return $this->insert($item);
    }
    public function getDetailList($where)
    {
        $res = $this->where($where)->group('goods_code')->select();
        return $res;
    }
}