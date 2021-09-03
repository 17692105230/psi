<?php
/**
 * Created by PhpStorm.
 * User: zll
 * Date: 2021/5/19
 * Time: 11:32
 */

namespace app\web\controller;

//盘点单

use app\Request;
use app\web\model\GoodsStock;
use app\web\model\InventoryOrders;
use think\Exception;
use think\facade\Db;
use app\web\validate\InventoryOrders as InventoryOrdersValidate;

class Inventory extends Controller
{
    //盘点单列表
    public function list(){
        $data = $this->request->get();
        $rows = $this->request->get("rows",10);
        $model = new InventoryOrders();
        $where[] = ['com_id',"=",$this->com_id];//
        $where[] = ['orders_status',"in",[1,9]];
        $list = $model->queryList($data,$where,$rows);

        return $this->renderSuccess($list);

    }

    //根据id获取盘点单
    public function get(Request $request){
        $orders_id = $request->get("orders_id",0);
        $model = new InventoryOrders();
        $res = $model->getByOrdersId_($orders_id);
        if(!$res){
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess($res);
    }

    //删除盘点单
    public function delete(){
        $orders_id = $this->request->get("orders_id",0);
        $model = new InventoryOrders();
        $result = $model->deleteRow($orders_id);
        if(!$result){
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess([],"删除成功");
    }

    //提交盘点单/修改盘点单
    public function submit(){
        $data = $this->request->post();
        $ioValidate = new InventoryOrdersValidate();
        $data['com_id'] = $this->com_id;
        $data['user_id'] = $this->user_id;
        //单据日期转时间戳
        if(isset($data['orders_date'])){
            $data['orders_date'] = strtotime($data['orders_date']);
        }

        if(!isset($data['orders_id']) || empty($data['orders_id'])){//新增
            $model = new InventoryOrders();
//            $data['com_id'] = $this->com_id;
            //验证数据
            if(!$ioValidate->scene("add")->check($data)){
                return $this->renderError($ioValidate->getError());
            }
            $result = $model->submit($data);
            if(!$result){
                return $this->renderError($model->getError());
            }
            return $this->renderSuccess([],"添加成功");
        }else{
            //编辑或者草稿转正式
            //查询单子
            $model = new InventoryOrders();
            $order = $model->where("orders_id","=",$data['orders_id'])->find();
            if(!$order){
                return $this->renderError("盘点单不存在");
            }
            //验证数据
            if(!$ioValidate->scene("edit")->check($data)){
                return $this->renderError($ioValidate->getError());
            }
            if($order['orders_status']==1 && $data['orders_status']==9){
                //说明是草稿转正式
                return $this->draftToOfficial($data);
            }else{
                //否则就是编辑
                return $this->editOrder($data);
            }

        }
    }

    //编辑草稿单
    public function editOrder($data){
//        $data = $this->request->post();
        $model = new InventoryOrders();
//        $data['com_id'] = $this->com_id;
        $result = $model->editOrder($data);
        if(!$result){
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess([],"编辑成功");
    }

    //拆解小程序提交的商品库存信息,变为行数据
//    public function dismantle($data){
//        $list = [];
//        foreach ($data as $goods){//商品层
//
//            foreach ($goods['list'] as $colors) {//颜色层
//
//                foreach ($colors['sizeList'] as $sizes) {
//                    $list[] = [
//
//                    ];
//                }
//
//            }
//        }
//        return $list;
//    }

    //草稿转正式
    public function draftToOfficial($data){
//        $orders_id = $this->request->get("orders_id",0);
        if(empty($data['orders_id'])){
            return $this->renderError("盘点单ID异常");
        }
//        $data["com_id"] = $this->com_id;
        $model = new InventoryOrders();
        $result = $model->draftToOfficial($data);
        if(!$result){
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess("成功");
    }
}