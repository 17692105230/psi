<?php


namespace app\web\controller;

use app\web\model\TransferOrders as TransferOrdersModel;
use app\web\validate\TransferOrders as TransferOrdersValidate;

/**
 * Class TransferOrders
 * 描述：仓库调拨单
 * @package app\web\controller
 * Date: 2021/1/16
 * Time: 17:27
 * @author mll
 */
class TransferOrders extends Controller
{
    //调拨单 列表
    public function list(){
        $data = $this->request->get();
        $rows = $this->request->get("rows",10);
        $model = new TransferOrdersModel();
        $where['com_id'] = $this->com_id;
        $list = $model->queryList($data,$where,$rows);

        return $this->renderSuccess($list);
    }

    //删除
    public function delete(){
        $orders_id = $this->request->get("orders_id",0);
        $model = new TransferOrdersModel();
        $result = $model->deleteRow($orders_id);
        if(!$result){
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess([],"删除成功");
    }

    //添加调拨单 草转正 编辑
    public function submit(){
        $data = $this->request->post();
        $data['com_id'] = $this->com_id;
        $data['user_id'] = $this->user_id;
        $model = new TransferOrdersModel();
        $toValidate = new TransferOrdersValidate();
        //单据日期转时间戳
        if(isset($data['orders_date'])){
            $data['orders_date'] = strtotime($data['orders_date']);
        }
        if(isset($data['orders_id']) && !empty($data['orders_id'])){
            //编辑或者草稿转正式
            $order = $model->where("orders_id","=",$data['orders_id'])->find();
            if(!$order){
                return $this->renderError("调拨单不存在");
            }
            //验证数据
            if(!$toValidate->scene("edit")->check($data)){
                return $this->renderError($toValidate->getError());
            }
            if($order['orders_status']==0 && $data['orders_status']==9){
                //草稿转正式
                return $this->draftToOfficial($data);
            }else{
                //编辑草稿
                return $this->editOrder($data);
            }
        }else{
            //验证数据
            if(!$toValidate->scene("add")->check($data)){
                return $this->renderError($toValidate->getError());
            }
            //新增草稿或正式单
            $result = $model->submit($data);
            if(!$result){
                return $this->renderError($model->getError());
            }
            return $this->renderSuccess([],"添加成功");
        }


    }

    //草稿转正式
    public function draftToOfficial($data){
        $orders_id = $data['orders_id'];
        if(empty($orders_id)){
            return $this->renderError("调拨单ID异常!");
        }
//        $data['com_id'] = $this->com_id;
        $model = new TransferOrdersModel();
        if(!$model->draftToOfficial($data)){
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess([],"调拨单转正式成功");
    }

    //编辑调拨单
    public function editOrder($data){
//        $data = $this->request->post();
//        $data['com_id'] = $this->com_id;
        $model = new TransferOrdersModel();
        $result = $model->editOrder($data);
        if(!$result){
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess([],"编辑成功");
    }

    //获取详情
    public function getByOrderId(){
        $orders_id = $this->request->get("orders_id",0);
        if(empty($orders_id)){
            return $this->renderError("缺少关键订单ID");
        }

        $data = (new TransferOrdersModel())->getByOrdersId_($orders_id);
        return $this->renderSuccess($data);
    }
}