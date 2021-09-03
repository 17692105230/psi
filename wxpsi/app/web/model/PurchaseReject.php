<?php
namespace app\web\model;

use Exception;
use app\common\model\web\PurchaseReject as PurchaseRejectModel;
use app\common\utils\BuildCode;
use think\facade\Db;
use app\web\model\Supplier as SupplierModel;
use app\web\model\DiarySupplier as DiarySupplierModel;
use app\web\model\PurchaseRejectDetails as PurchaseRejectDetailsModel;

use app\web\model\GoodsStock as GoodsStockModel;
use app\common\utils\Compare;

class PurchaseReject extends PurchaseRejectModel
{
    public function getPageList($where)
    {
        $query = $this->alias('o')
            ->join('hr_supplier s', 'o.supplier_id=s.supplier_id', 'left')
            ->where('o.com_id', $where['com_id'])
            // 供应商名称搜索
            ->when(isset($where['search_supplier_name']) && $where['search_supplier_name'], function ($query) use ($where) {
                $query->where('s.supplier_name', 'like', '%' . $where['search_supplier_name'] . '%');
            })
            // 单据编号
            ->when(isset($where['search_orders_code']) && $where['search_orders_code'], function ($query) use ($where) {
                $query->where('o.orders_code', 'like', '%' . $where['search_orders_code'] . '%');
            })
            // 单据状态
            ->when(isset($where['status']) && $where['status'] != -1, function ($query) use ($where) {
                $query->where('o.reject_status', $where['status']);
            })
            // 单据开始时间
            ->when(isset($where['search_date_end']) && $where['search_date_end'], function ($query) use ($where) {
                $query->where('o.orders_date', '>', strtotime($where['search_date_end']));
            })
            // 单据结束时间
            ->when(isset($where['search_date_end']) && $where['search_date_end'], function ($query) use ($where) {
                $query->where('o.orders_date', '<=', strtotime($where['search_date_end'] . ' +1 day'));
            });

        $total = $query->count();
        list($offset, $limit) = pageOffset($where);
        $rows = $query->field('o.*,s.supplier_name')->limit($offset, $limit)->select();

        return compact('total', 'rows');
    }

    public function getRejectOrderDetail($where)
    {
        return $this->alias('o')
            ->with('details')
            ->join('hr_supplier s', 'o.supplier_id=s.supplier_id', 'left')
            ->join('hr_organization z', 'o.warehouse_id = z.org_id', 'left')
            ->join('hr_settlement t', 't.settlement_id = o.settlement_id', 'left')
            ->where('o.com_id', $where['com_id'])
            ->where('o.orders_code', $where['orders_code'])
            ->field('o.*,s.supplier_name,z.org_name,t.settlement_name')
            ->find();
    }

    public function delRejectOrder($where)
    {
        $data = $this->getRejectOrderDetail($where);
        if (empty($data)) {
            $this->setError('订单不存在');
            return false;
        }
        if ($data['reject_status'] != 0) {
            $this->setError('订单状态不是草稿状态不能删除');
            return false;
        }
        return $data->together(['details'])->delete();
    }

    public function updateRejectOrder($data)
    {
        $detail_list = json_decode($data['details'], true);
        $data_insert = [];
        $data_delete = [];
        $data_update = [];
        // 空订单号为添加
        if (!isset($data['orders_code']) || empty($data['orders_code'])) {
            $data['orders_code'] = BuildCode::dateCode('PR');
            $info = $this;
            $data_insert = $detail_list;
        } else {
            $info = $this->getOne(['com_id' => $data['com_id'], 'orders_code' => $data['orders_code']]);

            if (empty($info)) {
                $this->setError('订单不存在');
                return false;
            }
            if ($info['lock_version'] != $data['lock_version']) {
                $this->setError('更新内容不是最新版本');
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
            $detailModel = new PurchaseRejectDetailsModel();
            // 要删除的detail
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
            // 要修改的 detail
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
            $detail_info = $this->getRejectOrderDetail(['com_id' => $info['com_id'], 'orders_code' => $info['orders_code']]);
            //            //查出仓库名称
//            $orgModel = new Organization();
//            $warehouse_name = $orgModel->where(['com_id' => $info['com_id'],
//                    'org_id' => $data['warehouse_id'],
//                'org_type' => 2])->field('org_name')->find();
//            //查询结算账户名称
//            $settlement = new Settlement();
//            $settlement_name = $settlement->where(['com_id' => $info['com_id'],
//                'settlement_id' => $data['settlement_id']
//            ])->field('settlement_name')->find();
//            //查询出来的信息 拼接到数组中
//            $detail_info['settlement_name'] = $settlement_name['settlement_name'];
//            $detail_info['warehouse_name'] = $warehouse_name['org_name'];

            //如果 == 9 代表是正式单据，需要对库存进行更改
            if ($info['reject_status'] == 9) {
                //采购退货类型
                $data['item_type'] = 9104;
                // 更新库存
                $this->baseSaveOrdersToStock($detail_info);
                //更新供应商对账
                $this->baseSaveOrdersToDiarySupplier($data);
                //更新供应商金额
                $this->baseSaveOrdersToSupplier($data);
            }
            Db::commit();
            return $info['orders_code'];
        } catch (Exception $e) {
            Db::rollback();
            $this->setError('修改失败:' . $e->getMessage());
            return false;
        }

    }

    public function revokeRejectPuchase($detail_info)
    {
        try {
            Db::startTrans();
            //更改库存，采购退货单撤销是 +库存
            foreach ($detail_info['details'] as $item) {
                $data = $item->toArray();
                $data['warehouse_id'] = $detail_info['warehouse_id'];
                $data['orders_code'] = $detail_info['orders_code'];
                $data['stock_number'] = $data['goods_number'];
                $goods_stock = (new GoodsStockModel())->updateOrInsert($data, '采购退货单撤销');
                if (!$goods_stock) {
                    throw new Exception('修改库存出错');
                }
            }
            $supplier_model = new SupplierModel();
            $where_supplier = [
                'supplier_id' => $detail_info['supplier_id'],
                'com_id' => $detail_info['com_id']
            ];
            $supplier_rmoney = $supplier_model->where($where_supplier)->field('supplier_money')->find();
            if (!$supplier_rmoney) {
                throw new Exception('为找到相关供应商');
            }
            $new_money['supplier_money'] = bcsub($supplier_rmoney['supplier_money'], $detail_info['orders_rmoney']);
            //将新金额更新到供应商表中
            $supplier_res = $supplier_model->where($where_supplier)->save($new_money);
            if (!$supplier_res) {
                throw new Exception('更新供应商金额出错');
            }
            Db::commit();
            return $detail_info;
        } catch (Exception $e) {
            Db::rollback();
            $this->setError('修改失败：' . $e->getMessage());
            return false;
        }
    }

    /**
     * Undocumented function 更新库存
     *
     * @param [type] $detail_info
     * @return void
     */
    public function baseSaveOrdersToStock($detail_info, $mul = -1)
    {

        foreach ($detail_info['details'] as $item) {
            $data = $item->toArray();
            $data['warehouse_id'] = $detail_info['warehouse_id'];
            $data['orders_code'] = $detail_info['orders_code'];
            $data['stock_number'] = $data['goods_number'] * $mul;
            $goods_stock = new GoodsStockModel();
            if (!$goods_stock->updateOrInsert($data, '采购退货单')) {
                throw new Exception('库存不足，无法退货');
            }
        }
    }

    /**
     * 函数描述:更新供应商金额（退货，+加供应商金额）
     * Date: 2021/1/20
     * Time: 11:00
     * @author mll
     */
    public function baseSaveOrdersToSupplier($data)
    {
        $supplier = new SupplierModel();
        $supplier_money = $supplier->getOne(['supplier_id' => $data['supplier_id']], 'supplier_money');
        if (!$supplier_money) {
            throw new Exception('为查找到相应供应商信息');
        }
        $new_supplier_money = bcadd($supplier_money['supplier_money'], $data['orders_rmoney']);
        $new = [
            'supplier_money' => $new_supplier_money
        ];
        if (!$supplier->where(['supplier_id' => $data['supplier_id']])->save($new)) {
            throw new Exception('更新供应商金额出错');
        }
    }

    /**
     * mll
     * 供应商对账信息
     * @param [type] $data
     * @return void
     */
    public function baseSaveOrdersToDiarySupplier($data)
    {
        //查出供应商对应的账户余额
        $supplier = new SupplierModel();
        $supplier_money = $supplier->getOne(['supplier_id' => $data['supplier_id']], 'supplier_money');
        if (!$supplier_money) {
            throw new Exception('供应商信息未找到');
        }
        $data['supplier_money'] = $supplier_money['supplier_money'];
        $diary_data = [
            'supplier_id' => $data['supplier_id'],
            'orders_code' => $data['orders_code'],
            'settlement_id' => $data['settlement_id'],
            'pmoney' => $data['orders_pmoney'],
            'rmoney' => $data['orders_rmoney'],
            'pbalance' => bcsub($data['orders_pmoney'], $data['orders_rmoney']),
            'supplier_balance' => $data['supplier_money'],
            'remark' => $data['orders_remark'],
            'com_id' => $data['com_id'],
            'item_type' => $data['item_type']
        ];
        //供应商对账model
        $diaryModel = new DiarySupplierModel();
        if (!$diaryModel->save($diary_data)) {
            throw new Exception('供应商对账信息添加失败');
        }
    }

    public function saveData($data)
    {
        return $this->save([
            'orders_code' => $data['orders_code'],
            'lock_version' => $data['lock_version'],
            'reject_remark' => isset($data['reject_remark']) ? $data['reject_remark'] : '',
            'supplier_id' => $data['supplier_id'],
            'settlement_id' => $data['settlement_id'],
            'warehouse_id' => $data['warehouse_id'],
            'orders_pmoney' => $data['orders_pmoney'],
            // 实收金额（非必填 不填是和应收金额一致）
            'orders_rmoney' => isset($data['orders_rmoney']) ? $data['orders_rmoney'] : $data['orders_pmoney'],
            'goods_number' => $data['goods_number'],
            'reject_status' => isset($data['reject_status']) ? $data['reject_status'] : 0,
            'orders_date' => strtotime($data['orders_date']),
            'com_id' => $data['com_id']
        ]);
    }
}