<?php


namespace app\web\controller;


use app\common\utils\BuildCode as BuildCodeUtil;
use app\Request;
use app\web\validate\MemberCardValidate;
use app\web\model\MemberCard as MemberCardModel;

class MemberCard extends Controller
{
    /**
     * 函数描述: 会员卡列表
     * Date: 2021/4/7
     * Time: 15:49
     * @author mll
     */
    public function listMemberCard()
    {
        $cardModel = new MemberCardModel();
        $res = $cardModel->where(['com_id' => $this->com_id])->order('member_accumulative_integral', 'asc')->select();
        return $this->renderSuccess($res);
    }

    /**
     * 函数描述: 加载会员卡详情
     * @param Request $request
     * Date: 2021/4/9
     * Time: 14:54
     * @author mll
     */
    public function loadCardInfo(Request $request)
    {
        $data = $request->get();
        $lock_version = $data['lock_version'];
        $oid = $data['oid'];
        $cardModel = new MemberCardModel();
        $res = $cardModel->where(['oid' => $oid, 'lock_version' => $lock_version, 'com_id' => $this->com_id])->find();
        if (!$res) {
            return $this->renderError('查询失败');
        }
        return $this->renderSuccess($res, '查询成功');

    }

    /**
     * 函数描述: 添加会员卡信息
     * @param Request $request
     * @return array
     * Date: 2021/4/7
     * Time: 10:09
     * @author mll
     */
    public function addMemberCard(Request $request)
    {
        $data = $request->post();
        $validate = new MemberCardValidate();
        $cardModel = new MemberCardModel();
        if (!$validate->scene('add')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $oid = isset($data['oid']) ? intval($data['oid']) : 0;//主键id
        $lock_version = isset($data['lock_version']) ? intval($data['lock_version']) : 0;//锁版本
        $openid = isset($data['openid']) ? $data['openid'] : "123";//微信openID
        $cardid = isset($data['cardid']) ? $data['cardid'] : "123";//会员卡类型ID
        $member_name = $data['member_name'];//会员姓名
        $member_sex = isset($data['member_sex']) ? $data['member_sex'] : "";//会员性别 0男，1女
        $member_phone = $data['member_phone'];//手机号
        $member_birthday = $data['member_birthday'];//生日
        $member_address = isset($data['member_address']) ? $data['member_address'] : "";//会员地址
        $card_time = time();//会员卡领取时间
        $member_status = isset($data['member_status']) ? $data['member_status'] : 0;//状态
        $member_accumulative_integral = intval(isset($data['member_accumulative_integral']) ? $data['member_accumulative_integral'] : 0);//会员累计积分
        $create_time = time();
        $update_time = time();
        if ($oid) {
            //为更新
            $exist = $cardModel->getOne(['oid' => $oid, 'lock_version' => $lock_version, 'com_id' => $this->com_id]);
            if (!$exist) {
                return $this->renderError('未找到对应版本', 400);
            }
            if ($exist['lock_version'] != $lock_version) {
                return $this->renderError("更新内容不是最新版本", 401);
            }
            $lock_version += $lock_version;
            $param = [
                'oid' => $oid,
                'lock_version' => $lock_version,
                'openid' => $openid,
                'cardid' => $cardid,
                'member_name' => $member_name,
                'member_sex' => $member_sex,
                'member_phone' => $member_phone,
                'member_birthday' => $member_birthday,
                'member_address' => $member_address,
                'card_time' => $card_time,
                'member_status' => $member_status,
                'member_accumulative_integral' => $member_accumulative_integral,
                'update_time' => time()
            ];
            $res = $cardModel->where(['oid' => $oid, 'com_id' => $this->com_id])->update($param);
            if (!$res) {
                return $this->renderError('更新会员卡信息失败');
            }
            return $this->renderSuccess('', '更新成功');
        }

        //根据姓名，手机号查询是否存在
        $repeat = $cardModel->getOne([
            'member_phone' => $member_phone
        ]);
        if ($repeat) {
            return $this->renderError('手机号已经存在', 400);
        }
        //生成会员卡编号
        $usercardcode = BuildCodeUtil::generateMemberCode();
        $res = $cardModel->insert([
            'openid' => $openid,
            'cardid' => $cardid,
            'usercardcode' => $usercardcode,
            'member_name' => $member_name,
            'member_sex' => $member_sex,
            'member_phone' => $member_phone,
            'member_birthday' => strtotime($member_birthday),
            'member_address' => $member_address,
            'card_time' => $card_time,
            'member_status' => $member_status,
            'member_accumulative_integral' => $member_accumulative_integral,//会员累计积分
            'com_id' => $this->com_id,
            'create_time' => $create_time,
            'update_time' => $update_time
        ]);
        if (!$res) {
            return $this->renderError('会员卡信息新增失败', 402);
        }
        return $this->renderSuccess('', '添加成功');
    }

    /**
     * 函数描述:修改会员信息
     * @param Request $request
     * Date: 2021/4/7
     * Time: 10:19
     * @author mll
     */
    public function editMemberCard(Request $request)
    {
        $data = $request->post();
        $validate = new MemberCardValidate();
        $cardModel = new MemberCardModel();
        if (!$validate->scene('edit')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $lock_version = $data['lock_version'];
        //根据id lock_version查询是否存在
        $exist = $cardModel->getOne(['oid' => $data['oid'], 'lock_version' => $lock_version]);
        if (!$exist) {
            return $this->renderError('未找到对应版本', 400);
        }
        $cardid = trim($data['cardid']);//会员卡类型ID
        $member_name = trim($data['member_name']);//会员姓名
        $member_sex = intval($data['member_sex']);//会员性别
        $member_phone = trim($data['member_phone']);//手机号
        $member_birthday = trim($data['member_birthday']);//生日
        $member_address = trim($data['member_address']);//会员地址
        $card_time = time();//会员卡领取时间
        $member_status = trim($data['member_status']);//状态
        $member_accumulative_integral = intval(trim($data['member_accumulative_integral']));//会员累计积分
        $model = $cardModel->where(['oid' => $data['oid'], 'lock_version' => $lock_version, 'com_id' => $this->com_id])->update([
            'cardid' => $cardid,
            'member_name' => $member_name,
            'member_sex' => $member_sex,
            'member_phone' => $member_phone,
            'member_birthday' => $member_birthday,
            'member_address' => $member_address,
            'card_time' => $card_time,
            'member_status' => $member_status,
            'member_accumulative_integral' => $member_accumulative_integral,
            'update_time' => time()
        ]);
        if (!$model) {
            return $this->renderError('更新会员信息失败', 402);
        }
        return $this->renderSuccess('更新成功');
    }

    /**
     * 函数描述: 删除
     * @param Request $request
     * Date: 2021/4/7
     * Time: 15:07
     * @author mll
     */
    public function delMemberCard(Request $request)
    {
        $data = $request->get();
        $validate = new MemberCardValidate();
        if (!$validate->scene('del')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $cardModel = new MemberCardModel();
        //查询是否已经有过记录


        //删除
        $res = $cardModel->where(['oid' => $data['oid'], 'lock_version' => $data['lock_version'], 'com_id' => $this->com_id])->delete();
        if (!$res) {
            return $this->renderError('删除会员卡失败', 402);
        }
        return $this->renderSuccess('', '删除成功');
    }

}