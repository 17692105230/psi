<?php


namespace app\common\model\web;


use app\common\model\BaseModel;

class PurchaseOrders extends BaseModel
{
    protected $table = 'hr_purchase_orders';
    protected $pk = 'orders_id';

    //关联供应商
    public function supplier(){
        return $this->hasOne(Supplier::class,"supplier_id","supplier_id");
    }
    //关联仓库
    public function warehouse(){
        return $this->hasOne(Organization::class,"org_id","warehouse_id");
    }
    //制单人
    public function user(){
        return $this->hasOne(User::class,"user_id","user_id");
    }

    public function details()
    {
        return $this->hasMany(PurchaseOrdersDetails::class,'orders_id',$this->pk)
            ->alias('d')
            ->join('hr_color c','c.color_id = d.color_id','left')
            ->join('hr_size s','s.size_id = d.size_id','left')
            ->join('hr_goods g','d.goods_code = g.goods_code','left')
            ->field('d.*,c.color_name,s.size_name,g.goods_name,g.goods_barcode');
    }

}