<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/6
 * Time: 16:33
 */

namespace app\common\model\web;


use app\common\model\BaseModel;

class PurchaseRejectDetails extends BaseModel
{
    protected $table = 'hr_purchase_reject_details';
    protected $pk = 'details_id';
}