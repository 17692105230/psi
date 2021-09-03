<?php
/**
 * 会员积分明细
 * User: zll
 * Date: 2021/6/10
 * Time: 11:34
 */

namespace app\common\model\web;


use app\common\model\BaseModel;

class MemberIntegralDetails extends BaseModel
{
    protected $table = "hr_member_integral_details";
    protected $pk = "oid";
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
}