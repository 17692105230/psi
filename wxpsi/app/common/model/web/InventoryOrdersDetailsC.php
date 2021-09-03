<?php
/**
 * Created by PhpStorm.
 * User: zll
 * Date: 2021/5/19
 * Time: 13:40
 */

namespace app\common\model\web;


use app\common\model\BaseModel;

class InventoryOrdersDetailsC extends BaseModel
{
    protected $table = "hr_inventory_orders_details_c";
    protected $pk = "details_id";

    public function color(){
        return $this->hasOne(Color::class,"color_id","color_id");
    }
    public function size(){
        return $this->hasOne(Size::class,"size_id","size_id");
    }
}