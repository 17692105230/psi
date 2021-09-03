<?php
/**
 * 销售订单.
 * User: ccl
 * Date: 2021/1/15
 * Time: 10:36
 */

namespace app\common\model\web;


use app\common\model\BaseModel;

class SalePlan extends BaseModel
{
    protected $table = 'hr_sale_plan';
    protected $pk = 'orders_id';

    public function details(){
        return $this->hasMany(SalePlanDetails::class,'orders_id',$this->pk)
            ->alias('d')
            ->join('hr_color c', 'c.color_id = d.color_id', 'left')
            ->join('hr_size s', 's.size_id = d.size_id', 'left')
            ->join('hr_goods g', 'd.goods_id = g.goods_id', 'left')
            ->field('d.*,c.color_name,s.size_name,g.goods_name,g.goods_barcode');
    }
}