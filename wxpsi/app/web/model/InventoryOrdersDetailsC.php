<?php
/**
 * Created by PhpStorm.
 * User: zll
 * Date: 2021/5/19
 * Time: 13:40
 */

namespace app\web\model;


use app\common\model\web\InventoryOrdersDetailsC as InventoryOrdersDetailsCModel;

class InventoryOrdersDetailsC extends InventoryOrdersDetailsCModel
{
    protected $name = "inventory_orders_details_c";
    protected $pk = "details_id";
}