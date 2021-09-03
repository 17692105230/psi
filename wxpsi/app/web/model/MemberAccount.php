<?php


namespace app\web\model;

use app\common\model\web\MemberAccount as MemberAccountModel;
class MemberAccount extends MemberAccountModel
{
    //为用户账户添加积分
    public function setIncIntegral($member_code,$points=0){
        if(empty($member_code) || empty($points)){
            $this->error = "会员编号或积分为空";
            return false;
        }
        return $this->where("member_id",$member_code)->inc("account_points",$points)->update();
    }
}