<?php
/**
 * 销售订单详情.
 * User: YB02
 * Date: 2021/1/15
 * Time: 10:39
 */

namespace app\common\model\web;


use app\common\model\BaseModel;

class SalePlanDetails extends BaseModel
{
    protected $table = 'hr_sale_plan_details';
    protected $pk = 'details_id';
}