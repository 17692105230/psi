<?php


namespace app\common\model\web;


use app\common\model\BaseModel;

class SaleOrders extends BaseModel
{
    protected $table = 'hr_sale_orders';
    protected $pk = 'orders_id';

    //关联客户
    public function client(){
        return $this->hasOne(ClientBase::class,"client_id","client_id");
    }
    //关联会员
    public function member(){
        return $this->hasOne(MemberBase::class,"member_id","member_id");
    }
    //关联仓库
    public function warehouse(){
        return $this->hasOne(Organization::class,"org_id","warehouse_id");
    }
    //制单人
    public function user(){
        return $this->hasOne(User::class,"user_id","user_id");
    }

    public function details(){
        return $this->hasMany(SaleOrdersDetails::class,'orders_id',$this->pk)
            ->alias('d')
            ->join('hr_color c', 'c.color_id = d.color_id', 'left')
            ->join('hr_size s', 's.size_id = d.size_id', 'left')
            ->join('hr_goods g', 'd.goods_id = g.goods_id', 'left')
            ->field('d.*,c.color_name,s.size_name,g.goods_name,g.goods_barcode');
    }
}