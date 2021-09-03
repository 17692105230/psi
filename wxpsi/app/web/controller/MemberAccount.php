<?php
/**
 * Created by PhpStorm.
 * User: zll
 * Date: 2021/6/9
 * Time: 10:00
 */

namespace app\web\controller;

use app\web\model\MemberAccount as MemberAccountModel;

class MemberAccount extends Controller
{
    //获取会员账户列表
    public function getListData()
    {
        $member_code = $this->request->get("member_code", 0);
        if (empty($member_code)) {
            return $this->renderError("会员账号错误");
        }
        $model = new MemberAccountModel();
        $list = $model
            ->where("com_id", $this->com_id)
            ->where("member_code", $member_code)
            ->select();

        return $this->renderSuccess($list);
    }

    //账户充值...
}