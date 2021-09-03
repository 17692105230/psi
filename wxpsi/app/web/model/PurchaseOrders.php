<?php


namespace app\web\model;

use app\common\model\web\PurchaseOrders as PurchaseOrdersModel;
use app\common\utils\BuildCode;
use app\web\model\GoodsStock as GoodsStockModel;
use app\web\model\PurchaseOrdersDetails as PurchaseOrdersDetailsModel;
use think\facade\Db;
use think\Exception;
use app\web\model\StockDiary as StockDiaryModel;
use app\web\model\Supplier as SupplierModel;
use app\web\model\DiarySupplier as DiarySupplierModel;
use app\web\model\Attachment as AttachModel;

class PurchaseOrders extends PurchaseOrdersModel
{

    public function saveOrders($data, $user)
    {
        try {
            //开始添加
            Db::startTrans();
            $detailModel = new PurchaseOrdersDetailsModel();
            if (!isset($data['orders_code']) || empty($data['orders_code'])) {
                //生成订单号
                $data['orders_code'] = BuildCode::dateCode('PO');
                $info = $this;
            } else {
                $info = $this->getOne(['com_id' => $data['com_id'], 'orders_code' => $data['orders_code']]);
                if (empty($info)) {
                    $this->setError("订单不存在");
                    return false;
                }
                //查询单据状态
                if ($info['orders_status'] == 9) {
                    $this->setError("已完成单据不允许做操作");
                    return false;
                }
                //判断锁版本是否正确
                if ($info['lock_version'] != $data['lock_version']) {
                    $this->setError("更新内容不是最新版本");
                    return false;
                }
                $data['lock_version'] = $data['lock_version'] + 1;
                $data['orders_id'] = $info['orders_id'];
                //判断是否为草稿单据
                if ($info['orders_status'] == 0) {
                    //删除此单据对应的商品详情
                    $where['orders_code'] = $data['orders_code'];
                    $where['com_id'] = $data['com_id'];
                    //先查询是否存在数据
                    $res_details = $detailModel->where($where)->select()->toArray();
                    if ($res_details) {
                        $detail_del = $detailModel->where($where)->delete();
                        if (!$detail_del) {
                            throw new Exception("删除失败");
                        }
                    }
                }

            }
            //创建当前时间
            $time_now = time();

            $data['orders_date'] = strtotime($data['orders_date']);
            $data['user_id'] = $user['user_id'];
            //添加订单，如果添加失败，则rollback所有操作
            if (!$info->saveData($data)) {
                throw new Exception('添加采购单失败');
            }
            $data['details'] = json_decode($data['details'], true);
            //循环取出details中的数据
            $rows = [];
            foreach ($data['details'] as $item) {
                $detail['orders_code'] = $data['orders_code'];
                $detail['orders_id'] = $info['orders_id'];
                $detail['goods_code'] = $item['goods_code'];
                $detail['color_id'] = $item['color_id'];
                $detail['size_id'] = $item['size_id'];
                $detail['goods_number'] = $item['goods_number'];
                $detail['goods_price'] = $item['goods_price'];
                $detail['goods_tmoney'] = $item['goods_tmoney'];
                $detail['create_time'] = $time_now;
                $detail['update_time'] = $time_now;
                $detail['com_id'] = $data['com_id'];
                $detail['goods_id'] = $item['goods_id'];
                array_push($rows, $detail);
            }
            $res = $detailModel->insertAll($rows);
            if (!$res) {
                throw new Exception("添加采购单详情失败");
            }
            /**
             * 如果orders_status == 9 ：正式
             * 1.添加相对应库存
             * 2.对供应商金额减少
             * 3.添加供应商对账记录
             */
            if ($data['orders_status'] == 9) {
                //采购支出 类型 编号
                $data['item_type'] = 9103;
                //添加相对应库存 在goods_stock添加记录
                $this->baseSaveOrdersToStock($data);
                //对供应商金额减少
                $this->baseSaveOrdersToSupplier($data);
                //添加供应商对账记录
                $this->baseSaveOrdersToDiarySupplier($data);
            }

            if (!empty($data['attachment'])) {
                $attachModel = new AttachModel();
                // 如果已存在附件则删除
                $attachModel->where('orders_id', '=', $info['orders_id'])->delete();
                // 附件保存
                $attach = json_decode($data['attachment'], true);
                $row = [
                    'orders_id' => $info['orders_id'],
                    'url' => $attach['assist_url'],
                    'extension' => $attach['extension'],
                    'size' => $attach['size'],
                    'key' => $attach['key'],
                    'name' => $attach['name'],
                    'category' => $attachModel::ATTACHMENT_CATEGORY_ATTACH,
                    'create_time' => time(),
                    'com_id' => $data['com_id'],
                ];
                $res = $attachModel->insert($row);
                if (!$res) {
                    Db::rollback();
                    $this->setError('附件上传失败');
                    return false;
                }
            }

            Db::commit();
            return $data['orders_code'];
        } catch (Exception $e) {
            Db::rollback();
            $this->setError('添加失败:' . $e->getMessage() . $e->getLine());
//            echo $e->getMessage();
            return false;
        }
    }

    /**
     * 函数描述: 采购单订单详情
     * @param $where
     * @return mixed
     * Date: 2021/1/11
     * Time: 16:01
     * @author mll
     */
    public function getOrderInfo($where)
    {
        return $this->alias('o')
            ->with('details')
            ->join('hr_supplier s', 'o.supplier_id = s.supplier_id', 'left')
            ->where('o.com_id', $where['com_id'])
            ->where('o.orders_code', $where['orders_code'])
            ->field('o.*,s.supplier_name')
            ->find();
    }

    /**
     * 函数描述:编辑采购单详情
     * @param $data
     * Date: 2021/1/12
     * Time: 10:47
     * @author mll
     */
    public function editDetail($data)
    {
        $res = $this->where(['orders_code' => $data['orders_code'], 'lock_version' => $data['lock_version'], 'orders_id' => $data['orders_id']])->find();
        try {
            if (!$res) {
                throw new Exception('订单版本不匹配，不可做更改');
            }
            //开始修改
            Db::startTrans();
            //循环需要更改的id进行数据更改
            foreach ($data['edit_detail'] as $edit) {
                $res_edit = $this->save($edit);
                if (!$res_edit) {
                    throw new Exception('修改订单详情失败');
                }
            }
            $res_orders = $this->save($data);
            if (!$res_orders) {
                throw new Exception('修改订单失败');
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->setError('添加失败:' . $e->getMessage() . $e->getLine());
            return false;
        }

    }

    /**
     * 函数描述: 添加商品库存
     * @param $stock_info
     * Date: 2021/3/18
     * Time: 17:23
     * @author mll
     */
    public function baseSaveOrdersToStock($stock_info)
    {
        foreach ($stock_info['details'] as $item) {
            $data = $item;
            $data['warehouse_id'] = $stock_info['warehouse_id'];
            $data['orders_code'] = $stock_info['orders_code'];
            $data['stock_number'] = $data['goods_number'];
            $data['com_id'] = $stock_info['com_id'];
            (new GoodsStockModel())->updateOrInsert($data, '采购单');
        }
    }

    /**
     * 函数描述: 更新供应商金额
     * @param $data
     * @throws Exception
     * Date: 2021/3/19
     * Time: 9:31
     * @author mll
     */
    public function baseSaveOrdersToSupplier($data)
    {
        $supplier = new SupplierModel();
        $getSupplierMoney = $supplier->getOne(['supplier_id' => $data['supplier_id']], 'supplier_money');
        if (!$getSupplierMoney) {
            throw new Exception('为查找到供应商信息');
        }
        $newMoney = bcsub($getSupplierMoney['supplier_money'], $data['orders_rmoney']);
        //更新 供应商金额
        if (!$supplier->where(['supplier_id' => $data['supplier_id']])->save(['supplier_money' => $newMoney])) {
            throw new Exception('更新供应商金额出错');
        }
    }

    /**
     * 函数描述: 添加供应商对账记录
     * @param $data
     * @throws Exception
     * Date: 2021/3/19
     * Time: 10:38
     * @author mll
     */
    public function baseSaveOrdersToDiarySupplier($data)
    {
        //查出 供应商账户余额
        $supplier = new SupplierModel();
        $supplier_money = $supplier->getOne(['supplier_id' => $data['supplier_id']], 'supplier_money');
        if (!$supplier_money) {
            throw new Exception('供应商信息未找到');
        }
        $data['supplier_money'] = $supplier_money['supplier_money'];
        $diray_data = [
            'supplier_id' => $data['supplier_id'],
            'orders_code' => $data['orders_code'],
            'settlement' => $data['settlement_id'],
            'rmoney' => $data['orders_rmoney'],
            'pmoney' => $data['orders_pmoney'],
            'pbalance' => bcsub($data['orders_pmoney'], $data['orders_rmoney']),
            'supplier_balance' => $data['supplier_money'],
            'remark' => $data['orders_remark'],
            'com_id' => $data['com_id'],
            'item_type' => $data['item_type'],
            'orders_date' => $data['orders_date'],
        ];
        $dirayModel = new DiarySupplierModel();
        if (!$dirayModel->save($diray_data)) {
            throw new Exception('添加供应商信息失败');
        }
    }

    public function getPurchaseList($where, $page, $rows)
    {
        $query = $this->alias('o')
            ->where('o.com_id', $where['com_id'])
            ->when(isset($where['orders_status']), function ($query) use ($where) {
                $query->where('o.orders_status', $where['orders_status']);
            })
            ->join('hr_supplier s', 's.supplier_id = o.supplier_id', 'left')
            ->join('hr_organization w', 'w.org_id = o.warehouse_id', 'left')
            ->field('o.orders_code,o.lock_version,o.orders_id,o.supplier_id,o.orders_date,o.orders_status,o.create_time,o.update_time,
            o.orders_rmoney,o.goods_number,s.supplier_name,w.org_name')
            ->order('o.orders_id', 'desc');
        $total = $query->count();
        $offset = ($page - 1) * $rows;
        $rows = $query->limit($offset, $rows)->select();
        return compact("total", "rows");
    }

    public function saveData($data)
    {
        return $this->save([
            'supplier_id' => $data['supplier_id'],
            'warehouse_id' => $data['warehouse_id'],
            'settlement_id' => $data['settlement_id'],
            'orders_date' => $data['orders_date'],
            'orders_rmoney' => $data['orders_rmoney'],
            'orders_remark' => isset($data['orders_remark']) ? $data['orders_remark'] : '',
            'lock_version' => $data['lock_version'],
            'other_type' => $data['other_type'],
            'other_money' => $data['other_money'],
            'erase_money' => $data['erase_money'],
            'goods_number' => $data['goods_number'],
            'orders_pmoney' => $data['orders_pmoney'],
            'orders_status' => $data['orders_status'],
            'orders_code' => $data['orders_code'],
            'com_id' => $data['com_id'],
            'user_id' => $data['user_id']
        ]);
    }

    public function getPurchaseAnalysis($where, $whereIn)
    {

        if ($where['time'] == "quarter") {
            $field = "CONCAT(YEAR(FROM_UNIXTIME(orders_date,'%Y-%m-%d')),'第',quarter(FROM_UNIXTIME(orders_date,'%Y-%m-%d')),'季度') date,SUM(goods_number) goods_number,orders_pmoney,SUM(orders_pmoney) orders_pmoney";
        } else {
            $field = "DATE_FORMAT(FROM_UNIXTIME(orders_date,'%Y-%m-%d'),'{$where['time']}') date,SUM(goods_number) goods_number,orders_pmoney,SUM(orders_pmoney) orders_pmoney";
        }
        return $this
            ->when($where['start_time'] != "" && $where['end_time'] != "", function ($query) use ($where) {
                $query->whereBetween('orders_date', [$where['start_time'], $where['end_time']]);
            })
            ->where('orders_status', $where['orders_status'])
            ->where($whereIn)
            ->group('date')
            ->field($field)
            ->select()->toArray();
    }

    /**
     * 函数描述:采购订单流转采购单
     * @param $data
     * @param $detail
     * Date: 2021/1/22
     * Time: 11:59
     */
    public function saveTransferData($data, $detail)
    {
        $res = $this->save([
            'orders_code' => $data['orders_code'],
            'supplier_id' => $data['supplier_id'],
            //合计金额
            'orders_pmoney' => $data['orders_pmoney'],
            'goods_number' => $data['goods_number'],
            'orders_status' => $data['orders_status'],
            'orders_remark' => $data['orders_remark'],
            'orders_date' => is_numeric($data['orders_date']) ? $data['orders_date'] : strtotime($data['orders_date']),
            'lock_version' => $data['lock_version'],
            'com_id' => $data['com_id'],
            'warehouse_id' => $data['warehouse_id']
        ]);
        if (!$res) {
            return false;
        }
        $orders_id = $this->orders_id;
        $purchaseDetailModel = new PurchaseOrdersDetailsModel();
        foreach ($detail as $key => $item) {
            $item['orders_id'] = $orders_id;
            $item['orders_code'] = $data['orders_code'];
            $res = $purchaseDetailModel->add($item);
            if (!$res) {
                return false;
            }
        }
        return true;
    }
}