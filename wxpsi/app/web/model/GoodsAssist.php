<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/5
 * Time: 18:05
 */

namespace app\web\model;

use app\common\model\web\GoodsAssist as GoodsAssistModel;

class GoodsAssist extends GoodsAssistModel
{
    public function deleteGoodsAssiest($where)
    {
        return $this->where($where)->delete();
    }

    public function getGoodsImages($goods_id)
    {
        return $this->where($goods_id)->select();
    }

    public function findsGoodsImage($where)
    {
        return $this->where($where)->find();
    }
}