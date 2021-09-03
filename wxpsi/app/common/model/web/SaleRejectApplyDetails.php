<?php
/**
 * 销售退货申请明细
 * User: YB02
 * Date: 2021/1/15
 * Time: 11:18
 */

namespace app\common\model\web;
use app\common\model\BaseModel;

class SaleRejectApplyDetails extends BaseModel
{
    protected $table = 'hr_sale_reject_apply_details';
    protected $pk = 'details_id';
    protected $autoWriteTimestamp = true;


}