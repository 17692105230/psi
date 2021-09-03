<?php


namespace app\web\model;
use app\common\model\web\Supplier as SupplierModel;

class Supplier extends SupplierModel
{

    /**
     *查询列表
     */
    public function getList($where, $field = '*', $order = 'create_time desc')
    {
        return parent::getList($where, $field, $order); // TODO: Change the autogenerated stub
    }
    /**
     * 更新列表
     */
    public function editSupplier($where,$update)
    {
        return $this->where($where)->save($update);
    }

    /**
     * 删除
     */
    public function delSupplier($where){
        return $this->where($where)->delete();
    }
}