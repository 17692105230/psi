<?php
/**
 * Created by PhpStorm.
 * User: zll
 * Date: 2021/5/19
 * Time: 11:58
 */

namespace app\common\model\web;


use app\common\model\BaseModel;

class InventoryOrdersChildren extends BaseModel
{
    protected $table = "hr_inventory_orders_children";
    protected $pk = "details_id";

    public function detailslist(){
        return $this->hasMany(InventoryOrdersDetails::class,"orders_id","orders_id");
    }

    public function detailsclist(){
        return $this->hasMany(InventoryOrdersDetailsC::class,"orders_id","orders_id");
    }
}