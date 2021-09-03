<?php


namespace app\web\model;

use app\common\model\web\DiarySupplier as DiarySupplierModel;
class DiarySupplier extends DiarySupplierModel
{
    public function queryListDiarySupplier($where)
    {
        $query = $this
            ->where('com_id',$where['com_id'])
            ->when(isset($where['search_supplier_id']) && $where['search_supplier_id'],function ($query) use ($where){
                $query->where('supplier_id',$where['search_supplier_id']);
            })
            ->when(isset($where['search_account_id']) && $where['search_account_id'],function ($query) use ($where){
                $query->where('account_id',$where['search_account_id']);
            })
            ->when(isset($where['search_settlement_id']) && $where['search_settlement_id'],function ($query) use ($where){
                $query->where('settlement_id',$where['search_settlement_id']);
            })
            ->when(isset($where['search_orders_code']) && $where['search_orders_code'],function ($query) use ($where){
                $query->where('orders_code',$where['search_orders_code']);
            })
            ->when(isset($where['search_user_id']) && $where['search_user_id'],function ($query) use ($where){
                $query->where('user_id',$where['search_user_id']);
            })
            ->when(isset($where['search_begin_date']) && $where['search_begin_date'],function ($query) use ($where){
                $query->where('create_time','>',$where['search_begin_date']);
            })
            ->when(isset($where['search_end_date']) && $where['search_end_date'], function ($query) use ($where){
               $query->where('create_time','<=',$where['search_end_date']);
            });
           $total = $query->count();
           list($offset,$limit) = pageOffset($where);
           $rows = $query->order('create_time desc')->limit($offset,$limit)->select();
           $rows[0]['pmoney_sum'] = $query->sum("pmoney");
           $rows[0]['rmoney_sum'] = $query->sum("rmoney");
           $rows[0]['pbalance_sum'] = $query->sum("pbalance");
           return compact('total','rows');
    }
    public function getDetailsByOrdersCode($where)
    {

        return $this->alias('ds')
            ->where(['ds.com_id'=>$where['com_id'],'ds.orders_code'=>$where['orders_code']])
            ->join('hr_settlement s','s.settlement_id = ds.settlement_id','left')
            ->join('hr_dict d','d.dict_id = ds.account_id','left')
            ->join('hr_user u','u.user_id = ds.user_id','left')
            ->join('hr_supplier sp','sp.supplier_id = ds.supplier_id','left')
            ->field('ds.*,s.settlement_name,d.dict_name,u.user_name,sp.supplier_money')
            ->find();
    }
}