<?php


namespace app\web\controller;


use app\Request;
use app\web\model\Supplier as SupplierModel;
use app\web\validate\Supplier as SupplierValidate;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use app\web\model\PurchaseOrders as PurchaseOrdersModel;
class Supplier extends Controller
{
    /**
     * 函数描述:获取供应商列表
     * @return \think\Collection
     * Date: 2021/1/8
     * Time: 11:51
     * @author mll
     */
    public function getSupplierList(){
        $supplierModel = new SupplierModel();
        $res = $supplierModel->getList(['com_id'=>$this->com_id]);
        if (!$res){
            return $this->renderError('查询出错');
        }
        return $this->renderSuccess($res,'查询成功');
    }

    /**
     * 函数描述:获取详情
     * @param Request $request
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * Date: 2021/1/11
     * Time: 10:18
     * @author mll
     */
    public function loadSupplierInfo(Request $request){
        $data = $request->get();
        $supplier = new SupplierModel();
        $validate = new SupplierValidate();
        if (!$validate->scene('del')->check($data)){
            return $this->renderError($validate->getError());
        }
        $lock_where = ['com_id'=>$this->com_id,'supplier_id'=>$data['supplier_id']];
        $lock = $supplier->where($lock_where)->find();
        if (empty($lock)){
            return $this->renderError('未找到相对应版本');
        }
        if (!$lock['lock_version'] == $data['lock_version']){
            return $this->renderError('版本信息不对应，无数据');
        }
        $res = $supplier->where(['com_id'=>$this->com_id,'supplier_id'=>$data['supplier_id']])->find();
        if (!$res){
            return $this->renderError('查询失败');
        }
        return $this->renderSuccess($res,'查询成功');
    }
    /**
     * 函数描述:供应商保存
     * @param Request $request
     * Date: 2021/1/8
     * Time: 11:57
     * @author mll
     */
    public function saveSupplier(Request $request){
        $data = $request->post();
        $validate = new SupplierValidate();
        $supplierModel = new SupplierModel();
        if (!$validate->scene('add')->check($data)){
            return $this->renderError($validate->getError());
        }
        $data_save = [
            'supplier_name' => $data['supplier_name'],
            'supplier_director' => $data['supplier_director'],
            'supplier_mphone' => $data['supplier_mphone'],
            'com_id' => $this->com_id
        ];
        if (!empty($data['supplier_discount'])){
            $data_save['supplier_discount'] = $data['supplier_discount'];
        }
        if (!empty($data['supplier_status'])){
            $data_save['supplier_status'] = $data['supplier_status'];
        }
        if (!empty($data['supplier_address'])){
            $data_save['supplier_address'] = $data['supplier_address'];
        }
        if (!empty($data['supplier_money'])){
            if (!is_numeric($data['supplier_money'])){
                return $this->renderError('金额格式错误');
            }
            $data_save['supplier_money'] = $data['supplier_money'];
        }
        $res = $supplierModel->save($data_save);
        if (!$res){
            return $this->renderError('添加失败，服务器出错');
        }
        return $this->renderSuccess('','添加成功');
    }

    /**
     * 函数描述:修改供应商
     * @param Request $request
     * Date: 2021/1/8
     * Time: 14:25
     * @author mll
     */
    public function editSupplier(Request $request){
        $data = $request->post();
        $validate = new SupplierValidate();
        $supplierModel = new SupplierModel();
        if (!$validate->scene('edit')->check($data)){
            return $this->renderError($validate->getError());
        }
        $whereLock = [
            'com_id' => $this->com_id,
            'supplier_id' => $data['supplier_id']
        ];
        $lock = $supplierModel->where($whereLock)->find();
        if (!$lock['lock_version'] == $data['lock_version']){
            return $this->renderError('版本信息不匹配，无法修改');
        }
        $where = [
            'supplier_id' => $data['supplier_id'],
            'com_id' => $this->com_id
        ];
        $update = [
            'supplier_name' => $data['supplier_name'],
            'supplier_director' => $data['supplier_director'],
            'supplier_mphone' => $data['supplier_mphone'],
            'supplier_discount' => $data['supplier_discount'],
            'supplier_status' => $data['supplier_status'],
            'supplier_address' => $data['supplier_address'],
            'lock_version' => $data['lock_version']+1
        ];
        $res = $supplierModel->editSupplier($where,$update);
        if (!$res){
            return $this->renderError('更新出错');
        }
        return $this->renderSuccess('','更新成功');
    }

    /**
     * 函数描述:删除供应商
     * @param Request $request
     * Date: 2021/1/8
     * Time: 14:45
     * @author mll
     */
    public function delSupplier(Request $request){
        $data = $request->post();
        $validate = new SupplierValidate();
        $supplierModel = new SupplierModel();
        if (!$validate->scene('del')->check($data)){
            return $this->renderError($validate->getError());
        }
        //先判断此供应商是否在采购单中使用过
        $purchaseModel = new PurchaseOrdersModel();
        $pur = $purchaseModel->where(['supplier_id' => $data['supplier_id'],'com_id'=>$this->com_id])->find();
        if ($pur){
            return $this->renderError('此供应商已经使用过，无法删除');
        }
        $lock_where = [
            'supplier_id' => $data['supplier_id'],
            'com_id' => $this->com_id
        ];
        $lock = $supplierModel->where($lock_where)->find();
        if (!$lock['lock_version'] == $data['lock_version']){
            return $this->renderError('版本不匹配');
        }
        $del_where = [
            'supplier_id' => $data['supplier_id'],
            'com_id' => $this->com_id
        ];
        $res = $supplierModel->delSupplier($del_where);
        if (!$res){
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('','删除成功');
    }
}