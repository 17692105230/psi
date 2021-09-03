<?php

namespace app\web\controller;
use app\Request;
use app\web\model\Settlement as SettlementModel;
use app\web\validate\Settlement as SettlementValidate;

class Settlement extends Controller
{
    /**
     * 函数描述:查询结算账户列表
     * @param Request $request
     * @return array
     * Date: 2021/1/8
     * Time: 18:05
     * @author mll
     */
    public function loadSettlement(Request $request)
    {
        $settlement = $request->post();
        $settlementModel = new SettlementModel();
        $res = $settlementModel->where(['com_id' => $this->com_id])->select();
        if (!$res){
            return $this->renderError('查询失败');
        }
        return $this->renderSuccess($res,'查询成功');
    }

    /**
     * 函数描述:添加结算账户
     * @param Request $request
     * Date: 2021/1/9
     * Time: 14:30
     * @author mll
     */
    public function saveSettlement(Request $request)
    {
        $data = $request->post();
        $validate = new SettlementValidate();
        $settlementModel = new SettlementModel();
        if (!$validate->scene('add')->check($data)){
            return $this->renderError($validate->getError());
        }
        $saveData['settlement_name'] = $data['settlement_name'];
        $saveData['settlement_status'] = $data['settlement_status'];
        if (!empty($data['settlement_code'])){
            $saveData['settlement_code'] = $data['settlement_code'];
        }
        if (!empty($data['account_holder'])){
            $saveData['account_holder'] = $data['account_holder'];
        }
        if (!empty($data['settlement_money'])){
            $saveData['settlement_money'] = $data['settlement_money'];
        }
        if (!empty($data['settlement_remark'])){
            $saveData['settlement_remark'] = $data['settlement_remark'];
        }
        $saveData['com_id'] = $this->com_id;
        $res = $settlementModel->save($saveData);
        if (!$res){
            return $this->renderError('账户添加出错');
        }
        return $this->renderSuccess('','添加成功');
    }

    /**
     * 函数描述:修改结算账户
     * @param Request $request
     * Date: 2021/1/9
     * Time: 15:08
     * @author mll
     */
    public function editSettlement(Request $request){
        $data = $request->post();
        $validate = new SettlementValidate();
        $settlementModel = new SettlementModel();
        if (!$validate->scene('edit')->check($data)){
            return $this->renderError($validate->getError());
        }
        //验证版本
        $lock_where = [
            'com_id' => $this->com_id,
            'settlement_id' => $data['settlement_id']
        ];
        $lock = $settlementModel->where($lock_where)->find();
        if (!$lock['lock_version'] == $data['lock_version']){
            return $this->renderError('版本信息不对应，不可以修改');
        }
        $where = [
            'com_id' => $this->com_id,
            'settlement_id' => $data['settlement_id']
        ];
        $update = [
            'settlement_name' => $data['settlement_name'],
            'settlement_code' => $data['settlement_code'],
            'account_holder' => $data['account_holder'],
            'account_type' => $data['account_type'],
            'settlement_remark' => $data['settlement_remark'],
            'lock_version' => $data['lock_version']+1,
            'settlement_money' => $data['settlement_money'],
            'settlement_status' => $data['settlement_status']
        ];
        $res = $settlementModel->where($where)->save($update);
        if (!$res){
            return $this->renderError('修改失败');
        }
        return $this->renderSuccess('','修改成功');
    }

    /**
     * 函数描述:删除结算账户
     * @param Request $request
     * Date: 2021/1/9
     * Time: 15:24
     * @author mll
     */
    public function delSettlement(Request $request){
        $data = $request->post();
        $validate = new SettlementValidate();
        $settlementModel = new SettlementModel();
        if (!$validate->scene('del')->check($data)){
            return $this->renderError($validate->getError());
        }
        $lock_where = [
            'com_id' => $this->com_id,
            'settlement_id' => $data['settlement_id']
        ];
        $lock = $settlementModel->where($lock_where)->find();
        if (empty($lock)){
            return $this->renderError('未找到相关信息不可以删除');
        }
        if (!$lock['lock_version'] == $data['lock_version']){
            return $this->renderError('版本信息不匹配，不可删除');
        }
        $where = [
            'com_id' => $this->com_id,
            'settlement_id' => $data['settlement_id']
        ];
        $res = $settlementModel->where($where)->delete();
        if (!$res){
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('','删除成功');

    }

    /**
     * 函数描述:结算账户回显数据
     * @param Request $request
     * Date: 2021/1/14
     * Time: 15:27
     * @author mll
     */
    public function loadSettlementInfo(Request $request){
        $data = $request->get();
        $validate = new SettlementValidate();
        $settlementModel = new SettlementModel();
        if (!$validate->scene('del')->check($data)){
            return $this->renderError($validate->getError());
        }
        $lock_where = [
            'settlement_id' => $data['settlement_id'],
            'lock_version' => $data['lock_version'],
            'com_id' => $this->com_id
        ];
        $lock = $settlementModel->where($lock_where)->find();
        if (!$lock){
            return $this->renderError('未找到相对应的信息');
        }
        if (!$lock['lock_version'] == $data['lock_version']){
            return $this->renderError('版本信息不正确，无法进行操作');
        }
        return $this->renderSuccess($lock,'查询成功');
    }

    //zll 获取结算列表
    public function getList(){
        $model = new SettlementModel();
        $list = $model->getList(['com_id' => $this->com_id, 'settlement_status' => 1]);
        return $this->renderSuccess($list);
    }


}