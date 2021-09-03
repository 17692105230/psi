<?php
/**
 * Created by PhpStorm.
 * User: zll
 * Date: 2021/5/19
 * Time: 11:59
 */

namespace app\common\model\web;


use app\common\model\BaseModel;

class InventoryOrdersDetails extends BaseModel
{
    protected $table = "hr_inventory_orders_details";
    protected $pk = "details_id";

    public function deleteByOrderId($orders_id){
        return $this
            ->where("orders_id","=",$orders_id)
            ->delete();
    }

    public function goods(){
        return $this->hasOne(Goods::class,"goods_id","goods_id");
    }
    public function color(){
        return $this->hasOne(Color::class,"color_id","color_id");
    }
    public function size(){
        return $this->hasOne(Size::class,"size_id","size_id");
    }
}