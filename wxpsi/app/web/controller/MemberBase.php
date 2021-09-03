<?php


namespace app\web\controller;

use app\web\model\MemberBase as MemberBaseModel;
use app\web\model\MemberAccount as MemberAccountModel;
use app\web\validate\MemberBase as MemberBaseValidate;
use app\common\utils\BuildCode as BuildCodeUtil;

use app\Request;
use think\Exception;

class MemberBase extends Controller
{

    public function increaseMember(Request $request)
    {
        $data = $request->post();
    }

    /**
     * 添加编辑会员
     * @param Request $request
     * @return array
     */
    public function addMember(Request $request)
    {
        $params = $request->post();
        $com_id = $this->com_id;
        $member_id = $request->param("member_id", 0);
        $member_name = trim($params['member_name']);
        $member_phone = trim($params['member_phone']);
        $member_idcode = trim($params['member_idcode']);
//        $user_code = isset($params['user_code']) ? trim($params['user_code']) : '';
        $member_qq = isset($params['member_qq']) ? trim($params['member_qq']) : '';
        $member_wechat = isset($params['member_wechat']) ? trim($params['member_wechat']) : '';
        $member_sex = isset($params['member_sex']) ? intval($params['member_sex']) : 1;
        $category_code = isset($params['category_code']) ? trim($params['category_code']) : 0;
        $member_age = isset($params['member_age']) ? intval($params['member_age']) : 0;
        $member_height = isset($params['member_height']) ? intval($params['member_height']) : 0;
        $city_code = isset($params['city_code']) ? trim($params['city_code']) : 0;
        $member_email = isset($params['member_email']) ? trim($params['member_email']) : 0;
        $member_birthday = isset($params['member_birthday']) ? trim($params['member_birthday']) : 0;
        $member_address = isset($params['member_address']) ? trim($params['member_address']) : '';
        $member_story = isset($params['member_story']) ? trim($params['member_story']) : '';
        $member_status = isset($params['member_status']) ? intval($params['member_status']) : 1;

        $memberBaseValidate = new MemberBaseValidate();
        if (!$memberBaseValidate->scene('add')->check($params)) {
            return $this->renderError($memberBaseValidate->getError());
        }
        $memberBase = new MemberBaseModel();
        // 查询是否存在

        $exist = json_decode($memberBase->getOne(function ($query) use ($member_phone, $member_idcode, $member_id, $com_id) {
            $query = $query->where("com_id", $com_id);

            if (!empty($member_id)) {
                $query = $query->where("member_id", "<>", $member_id);
            }

            $query = $query->where(function ($q) use ($member_phone, $member_idcode) {
                return $q->whereOr("member_phone", $member_phone)
                    ->whereOr("member_idcode", $member_idcode);
            });
            return $query;
        }), true);

        if ($exist) {
            return $this->renderError("手机或证件号已存在", 400);
        }
        if (empty($member_id)) {//新增会员
            $memberBase->startTrans();
            try{
                $member_code = BuildCodeUtil::generateMemberCode();
                //基础信息
                $baseId = $memberBase->insert([
                    'member_code' => $member_code,
                    'member_name' => $member_name,
                    'member_phone' => $member_phone,
                    'member_idcode' => $member_idcode,
                    'user_code' => $this->user_id,
                    'member_qq' => $member_qq,
                    'member_wechat' => $member_wechat,
                    'member_sex' => $member_sex,
                    'category_code' => $category_code,
                    'member_age' => $member_age,
                    'member_height' => $member_height,
                    'city_code' => $city_code,
                    'member_email' => $member_email,
                    'member_birthday' => $member_birthday,
                    'member_address' => $member_address,
                    'member_story' => $member_story,
                    'member_status' => $member_status,
                    'create_time' => time(),
                    'com_id' => $com_id,
                ], true);
                if (!$baseId) {
                    throw new \Exception("添加会员信息失败",401);
                }
                //会员账户信息
                $memberAccount = new MemberAccountModel();
                $ok = $memberAccount->insert([
                    'member_code' => $member_code,
                    'create_time' => time(),
                    'com_id' => $com_id,
                ]);
                if (!$ok) {
                    throw new \Exception("添加会员信息失败",402);
                }
                //会员卡
                $memberBase->commit();
                return $this->renderSuccess([],"添加会员成功");
            }catch(\Exception $e){
                $memberBase->rollback();
                return $this->renderError($e->getMessage(), $e->getCode());
            }
        } else {//编辑会员
            $nowMember = $memberBase->where("member_id", $member_id)->find();
            if (!$nowMember) {
                return $this->renderError("无此会员", 400);
            }

            $nowMember->member_name = $member_name;
            $nowMember->member_phone = $member_phone;
            $nowMember->member_idcode = $member_idcode;
            $nowMember->member_qq = $member_qq;
            $nowMember->member_wechat = $member_wechat;
            $nowMember->member_sex = $member_sex;
            $nowMember->category_code = $category_code;
            $nowMember->member_age = $member_age;
            $nowMember->member_height = $member_height;
            $nowMember->city_code = $city_code;
            $nowMember->member_email = $member_email;
            $nowMember->member_birthday = $member_birthday;
            $nowMember->member_address = $member_address;
            $nowMember->member_story = $member_story;
            $nowMember->member_status = $member_status;

            if (!$nowMember->save()) {
                return $this->renderError("编辑失败!");
            }
            return $this->renderSuccess([], "编辑成功");
        }

    }

    //获取会员列表
    public function getListData(Request $request)
    {
        $where = $request->get();
        $where['com_id'] = $this->com_id;
        $list = (new MemberBaseModel())->getListData($where);
        return $this->renderSuccess($list);
    }

    //获取会员详情
    public function getDetail(Request $request){
        $member_id = $request->get("member_id",0);
        if(empty($member_id)){
            return $this->renderError("会员id错误");
        }
        $mbModel = new MemberBaseModel();
        $member = $mbModel->where("member_id","=",$member_id)->find();
        if(!$member){
            return $this->renderError("未找到此会员");
        }
        return $this->renderSuccess($member);
    }

    //修改会员状态
    public function editStatus(Request $request)
    {
        $member_id = $request->get("member_id", 0);
        if (empty($member_id)) {
            return $this->renderError("会员ID异常");
        }
        $where['member_id'] = $member_id;
        $where['com_id'] = $this->com_id;
        $model = new MemberBaseModel();
        $res = $model->editStatus($where);
        if ($res) {
            return $this->renderSuccess([], "状态修改成功");
        }
        return $this->renderError("状态修改失败");
    }



}