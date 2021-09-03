<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/15
 * Time: 11:58
 */

namespace app\web\model;
use app\common\model\web\SaleRejectApply as SaleRejectApplyModel;
use app\common\utils\BuildCode;
use app\web\model\SaleRejectApplyDetails as SaleRejectApplyDetailsModel;
use app\web\model\SaleRejectOrders as SaleRejectOrdersModel;
use app\web\model\SaleRejectOrdersDetails as SaleRejectOrdersDetailsModel;
use think\facade\Db;

class SaleRejectApply extends SaleRejectApplyModel
{
    public function loadData($where,$page,$rows){
        $query = $this->alias('sra')
            ->join('hr_client_base cb', 'sra.client_id = cb.client_id', 'left')
            ->where(['sra.com_id' => $where['com_id']])
            //单据状态
            ->when(isset($where['status']) && $where['status'] != -1, function ($query) use ($where) {
                $query->where(['orders_status' => $where['status']]);
            })
            //客户名字
            ->when(isset($where['search_client_name']) && $where['search_client_name'], function ($query) use ($where) {
                $query->where('cb.client_name', 'like', '%' . $where['search_client_name'] . '%');
            })
            //销售订单号
            ->when(isset($where['search_orders_code']) && $where['search_orders_code'], function ($query) use ($where) {
                $query->where('sra.orders_code', 'like', '%' . $where['search_orders_code'] . '%');
            })
            // 单据开始时间
            ->when(isset($where['search_date_begin']) && $where['search_date_begin'], function ($query) use ($where) {
                $query->where('sra.orders_date', '>', strtotime($where['search_date_begin']));
            })
            // 单据结束时间
            ->when(isset($where['search_date_end']) && $where['search_date_end'], function ($query) use ($where) {
                $query->where('sra.orders_date', '<=', strtotime($where['search_date_end'] . ' +1 day'));
            });
        $total = $query->count();
        $offset = ($page - 1) * $rows;
        $rows = $query->limit($offset, $rows)->field('sra.*,cb.client_name')->select();
        return compact('total', 'rows');
    }
    
    public function getRejectApply($where){
        return $this->alias('sra')
            ->with('details')
            ->join('hr_client_base cb','sra.client_id=cb.client_id','left')
            ->where('sra.com_id', $where['com_id'])
            ->where('sra.orders_code', $where['orders_code'])
            ->field('sra.*,cb.client_name')
            ->find();
    }

    public function delRejectApply($where){
        $data = $this->getRejectApply($where);
        if (empty($data)) {
            $this->setError('销售退货申请不存在');
            return false;
        }
        if ($data['orders_status'] != 0) {
            $this->setError('销售退货申请状态不是草稿状态不能删除');
            return false;
        }
        return $data->together(['details'])->delete();
    }

    /**
     * Notes:更新销售退货单
     * User:ccl
     * DateTime:2021/1/29 17:57
     * @param $data
     * @return bool|mixed
     */
    public function updateRejectApply($data){
        // 单据编号为空则为添加
        if (!isset($data['orders_code']) || empty($data['orders_code'])) {
            $data['orders_code'] = BuildCode::dateCode('SRA');
            $info = $this;
        } else {
            $info = $this->getOne(['com_id' => $data['com_id'], 'orders_code' => $data['orders_code']]);
            if (empty($info)) {
                $this->setError('销售退货申请不存在');
                return false;
            }
            if ($info['orders_status'] == 9) {
                $this->setError('已完成单据不允许操作');
                return false;
            }
            if ($info['lock_version'] != $data['lock_version']) {
                $this->setError('更新内容不是最新的版本');
            }
            $data['lock_version'] = $data['lock_version'] + 1;
        }
        $time_now = time();
        Db::startTrans();
        try {
            //更新salePlan表
            $info->saveData($data);
            $orders_code = $data['orders_code'];
            $detailModel = new SaleRejectApplyDetailsModel();
            //删除detail
            $data_delete = json_decode($data['data_delete'], true);
            if ($data_delete && is_array($data_delete)) {
                $delete_details_ids = array_column($data_delete, 'details_id');
                $res = $detailModel->where('details_id', 'in', $delete_details_ids)
                    ->where('orders_code', $orders_code)
                    ->delete();
                if (!$res) {
                    throw  new Exception('删除退货申请详情失败');
                }
            }
            //添加detail
            $data_insert = json_decode($data['data_insert'], true);
            if ($data_insert && is_array($data_insert)) {
                foreach ($data_insert as $item) {
                    $item = array_merge($item, [
                        'com_id' => $data['com_id'],
                        'time_now' => $time_now,
                        'orders_id' => $info['orders_id'],
                        'orders_code' => $info['orders_code'],
                    ]);
                    $res1 = $detailModel->add($item);
                    if (!$res1) {
                        throw new Exception('添加退货申请详情失败');
                    }
                }
            }
            //修改details
            $data_update = json_decode($data['data_update'], true);
            if ($data_update && is_array($data_update)) {
                foreach ($data_update as $val) {
                    $detailInfo = $detailModel->getOne(['details_id' => $val['details_id'], 'orders_code' => $orders_code]);
                    if (empty($detailInfo)) {
                        throw new Exception('更新退货申请订单不存在');
                    }
                    if ($detailInfo['lock_version'] != $val['lock_version']) {
                        throw new Exception('退货申请不是最新版本');
                    }
                    // 指定的字段
                    $val['lock_version'] = $val['lock_version'] + 1;
                    $val['com_id'] = $info['com_id'];
                    $val['orders_id'] = $info['orders_id'];
                    $val['orders_code'] = $info['orders_code'];
                    $detailInfo->saveData($val);
                }
            }
            //如果状态为正式则流转至销售退货单
               if($data['orders_status'] == 9){
                $sale_order = new SaleRejectOrdersModel();
                //获取detail
                $sale_reject__apply_details = new SaleRejectApplyDetailsModel();
                $transfer_data_details = $sale_reject__apply_details->getList(['com_id' => $data['com_id'],'orders_code' => $data['orders_code']])->toArray();
                $transfer_data = $data;
                //状态改为销售单的订单状态
                $transfer_data['orders_status'] = 8;
                $transfer_data['orders_code'] = BuildCode::dateCode('SRO');
                $res = $sale_order->saveTransferData($transfer_data,$transfer_data_details);
                if(!$res){
                    throw new Exception('流转至销售退货单失败');
                }
            }
            Db::commit();
            return $info['orders_code'];
        } catch (Exception $e) {
            Db::rollback();
            $this->setError('修改失败:' . $e->getMessage() . $e->getLine());
            return false;
        }
    }
    public function saveData($data){
        return $this->save([
            'orders_code' => $data['orders_code'],
            'lock_version' => $data['lock_version'],
            'orders_remark' => $data['orders_remark'],
            'client_id' => $data['client_id'],
            'user_id' => $data['user_id'],
            'orders_pmoney' => $data['orders_pmoney'],
            'goods_number' => $data['goods_number'],
            'orders_status' => isset($data['orders_status']) ? $data['orders_status'] : 0,
            'orders_date' => strtotime($data['orders_date']),
            'com_id' => $data['com_id']
        ]);
    }
}