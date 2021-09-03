<?php


namespace app\common\model\web;


use app\common\model\BaseModel;

class PurchasePlan extends BaseModel
{
    protected $table="hr_purchase_plan";
    protected $pk = 'orders_id';

    public function details()
    {
        return $this->hasMany(PurchasePlanDetails::class, 'orders_id', $this->pk)
            ->alias('d')
            ->join('hr_color c', 'c.color_id = d.color_id', 'left')
            ->join('hr_size s', 's.size_id = d.size_id', 'left')
            ->join('hr_goods g', 'd.goods_code = g.goods_code', 'left')
            ->field('d.*,c.color_name,s.size_name,g.goods_name,g.goods_barcode');
    }

}