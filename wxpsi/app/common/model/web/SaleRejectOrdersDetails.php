<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/15
 * Time: 14:18
 */

namespace app\common\model\web;
use app\common\model\BaseModel;

class SaleRejectOrdersDetails extends BaseModel
{
    protected $table = 'hr_sale_reject_orders_details';
    protected $pk = 'details_id';
}