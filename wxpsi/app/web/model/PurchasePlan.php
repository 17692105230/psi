<?php


namespace app\web\model;

use app\common\model\web\PurchasePlan as PurchasePlanModel;
use app\web\model\PurchaseOrders as PurchaseOrdersModel;
use app\web\model\PurchasePlanDetails as PurchasePlanDetailsModel;
use think\facade\Db;
use Exception;
use app\common\utils\BuildCode;
use app\common\utils\Compare;

class PurchasePlan extends PurchasePlanModel
{
    public function getPageList($where)
    {

        $query = $this->alias('p')
            ->join('hr_supplier s', 'p.supplier_id=s.supplier_id', 'left')
            ->where('p.com_id', $where['com_id'])
            // 供应商名称搜索
            ->when(isset($where['supplier_name']) && $where['supplier_name'], function ($query) use ($where) {
                $query->where('s.supplier_name', 'like', '%' . $where['supplier_name'] . '%');
            })
            // 单据编号
            ->when(isset($where['orders_code']) && $where['orders_code'], function ($query) use ($where) {
                $query->where('p.orders_code', 'like', '%' . $where['orders_code'] . '%');
            })
            // 单据状态
            ->when(isset($where['orders_status']) && $where['orders_status'] != -1, function ($query) use ($where) {
                $query->where('p.orders_status', $where['orders_status']);
            })
            // 单据开始时间
            ->when(isset($where['search_date_begin']) && $where['search_date_begin'], function ($query) use ($where) {
                $query->where('p.orders_date', '>', strtotime($where['search_date_begin']));
            })
            // 单据结束时间
            ->when(isset($where['search_date_end']) && $where['search_date_end'], function ($query) use ($where) {
                $query->where('p.orders_date', '<=', strtotime($where['search_date_end'] . ' +1 day'));
            });

        $total = $query->count();
        list($offset, $limit) = pageOffset($where);
        $rows = $query->limit($offset, $limit)->field('p.*,s.supplier_name')->select();
        return compact('total', 'rows');
    }

    //获取采购订单详细信息
    public function getPlanDetail($where)
    {
        return $this->alias('p')
            ->with('details')
            ->join('hr_supplier s', 'p.supplier_id=s.supplier_id', 'left')
            ->join('hr_organization o', 'p.warehouse_id = o.org_id', 'left')
            ->where('p.com_id', $where['com_id'])
            ->where('p.orders_code', $where['orders_code'])
            ->field('p.*,s.supplier_name,o.org_name')
            ->find();
    }

    //删除采购订单
    public function delPlan($where)
    {
        $data = $this->getPlanDetail($where);
        if (empty($data)) {
            $this->setError('订单不存在');
            return false;
        }
        if ($data['orders_status'] != 0) {
            $this->setError('订单状态不是草稿状态不能删除');
            return false;
        }
        return $data->together(['details'])->delete();
    }

    public function updateOrder($data)
    {
        $detail_list = json_decode($data['details'], true);
        $data_insert = [];
        $data_delete = [];
        $data_update = [];

        // 空订单号为添加
        if (!isset($data['orders_code']) || empty($data['orders_code'])) {
            $data['orders_code'] = BuildCode::dateCode('PO');
            $info = $this;
            $data_insert = $detail_list;

        } else {
            $info = $this->getOne(['com_id' => $data['com_id'], 'orders_code' => $data['orders_code']]);
            //  var_dump($info->details->toArray());die;
            if (empty($info)) {
                $this->setError('订单不存在');
                return false;
            }
            /* 查询单据状态 */
            if ($info['orders_status'] == 9) {
                $this->setError('已完成单据不允许操作~~');
                return false;
            }
            /* 查看锁版本是否正确 */
            if ($info['lock_version'] != $data['lock_version']) {
                $this->setError('更新内容不是最新版本');
                return false;
            }
            $data['lock_version'] = $data['lock_version'] + 1;
            // 确定一下要 修改 删除 添加的
            $compare = new Compare($info->details->toArray(), $detail_list, ['color_id', 'size_id', 'goods_id']);
            $compare->handle();
            $compare->getAllResult($data_insert, $data_update, $data_delete);

        }

        $time_now = time();
        // 开始修改
        Db::startTrans();
        try {
            //更新order表
            $info->saveData($data);
            $orders_code = $data['orders_code'];
            $detailModel = new PurchasePlanDetailsModel();
            // 要删除的detail
            foreach ($data_delete as $detele_item) {
                $where = $compare->getWhere($detele_item, ['orders_id' => $info['orders_id']]);
                $detailModel->where($where)->delete();
            }
            // 要添加的detail
            if ($data_insert) {
                foreach ($data_insert as $item) {
                    $item = array_merge($item, [
                        'com_id' => $data['com_id'],
                        'time_now' => $time_now,
                        'orders_id' => $info['orders_id'],
                        'orders_code' => $info['orders_code'],
                    ]);
                    $res1 = $detailModel->add($item);
                    if (!$res1) {
                        throw new Exception('添加订单详情失败');
                    }
                }
            }
            // 要修改的
            foreach ($data_update as $val) {
                $where = $compare->getWhere($val, ['orders_id' => $info['orders_id']]);
                $detailInfo = $detailModel->getOne($where);
                if (empty($detailInfo)) {
                    throw new Exception('更新订单不存在');
                }
                // 指定的字段
                $val['lock_version'] = $detailInfo['lock_version'] + 1;
                $val['com_id'] = $info['com_id'];
                $val['orders_id'] = $info['com_id'];
                $val['orders_code'] = $info['orders_code'];
                $detailInfo->saveData($val);
            }


            //判断订单状态是否为9，为9是正式的，需要复制一份信息添加在采购单中
            if ($data['orders_status'] == 9) {
                $purchaseModel = new PurchaseOrdersModel();
                $transfer_data_details = $detailModel->getList(['com_id' => $data['com_id'], 'orders_code' => $orders_code]);
                $transfer_data = $data;
                //修改订单状态到采购单表中
                $transfer_data['orders_status'] = 0;
                //将销售订单号修改为新生成的采购单号
                $transfer_data['orders_code'] = BuildCode::dateCode('PO');
                $res = $purchaseModel->saveTransferData($transfer_data, $transfer_data_details);
                if (!$res) {
                    throw new Exception('流转到采购订单失败');
                }
            }
            Db::commit();
            return $info['orders_code'];
        } catch (Exception $e) {
            Db::rollback();
            $this->setError('修改失败:' . $e->getMessage());
            return false;
        }

    }

    public function saveData($data)
    {
        return $this->save([
            'orders_code' => $data['orders_code'],
            'lock_version' => $data['lock_version'],
            'orders_remark' => $data['orders_remark'],
            'supplier_id' => $data['supplier_id'],
            'warehouse_id' => $data['warehouse_id'],
            'orders_pmoney' => $data['orders_pmoney'],
            'goods_number' => $data['goods_number'],
            'orders_status' => isset($data['orders_status']) ? $data['orders_status'] : 0,
            'orders_date' => strtotime($data['orders_date']),
            'com_id' => $data['com_id'],
        ]);
    }

}