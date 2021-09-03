<?php


namespace app\web\model;

use app\common\model\web\SaleOrders as SaleOrdersModel;
use app\web\model\Attachment as AttachModel;
use app\web\model\ClientAccount as ClientAccountModel;
use app\web\model\DiaryClient as DiaryClientModel;
use app\web\model\GoodsStock as GoodsStockModel;
use app\web\model\SaleOrdersDetails as SaleOrdersDetailsModel;
use app\common\utils\BuildCode;
use app\web\model\Settlement as SettlementModel;
use think\Exception;
use think\facade\Db;

class SaleOrders extends SaleOrdersModel
{

    /** @var int 零售单 */
    public const ORDERS_ORDER_TYPE_RETAIL = 1;
    /** @var int 销售单 */
    public const ORDERS_ORDER_TYPE_SALE = 0;

    /** 销售单状态 */
    /** 草稿 */
    public const ORDERS_STATUS_DRAFT = 0;
    /** 销售单*/
    public const ORDERS_STATUS_REGULAR = 9;

    public function saveTransferData($data, $plan_data_details)
    {
        $res = $this->save([
            'orders_code' => $data['orders_code'],
            'client_id' => $data['client_id'],
            'orders_pmoney' => $data['orders_pmoney'],
            'goods_number' => $data['goods_number'],
            'user_id' => $data['user_id'],
            'orders_status' => $data['orders_status'],
            'orders_remark' => $data['orders_remark'],
            'orders_date' => strtotime($data['orders_date']),
            'com_id' => $data['com_id']
        ]);
        if (!$res) {
            return false;
        }
        $orders_id = $this->orders_id;
        $sale_order_details = new SaleOrdersDetailsMOdel();
        foreach ($plan_data_details as $key => $item) {
            $item['orders_id'] = $orders_id;
            $item['orders_code'] = $data['orders_code'];
            $res = $sale_order_details->addTransfer($item);
            if (!$res) {
                return false;
            }
        }
        return true;
    }

    public function saveOrders($data, $user)
    {
        $data['profit'] = $this->_calcProfit($data['details']);
        if (!isset($data['orders_code']) || empty($data['orders_code'])) {
            // 销售单不存在
            return $this->_insertSale($data, $user);
        }
        return $this->_updateSale($data, $user);
    }

    /**
     * 计算毛利
     * @param $details
     * @return int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function _calcProfit($details)
    {
        $details = json_decode($details, true);
        $goods_ids = array_column($details, 'goods_id');
        $goodsModel = new Goods();
        $goodsList = array_column(json_decode($goodsModel->whereIn('goods_id', $goods_ids)->select(), true), null, 'goods_id');
        $profit = 0; // 毛利
        foreach ($details as $detail) {
            $goods = $goodsList[$detail['goods_id']];
            $profit += ($detail['goods_price'] - $goods['goods_pprice']) * $detail['goods_number'];
        }
        return $profit;
    }

    /**
     * 更新销售单
     * @param null $data
     * @param null $user
     * @return mixed
     */
    private function _updateSale($data = null, $user = null)
    {
        $order = $this->getOne(['com_id' => $data['com_id'], 'orders_code' => $data['orders_code']]);
        if (empty($order)) {
            $this->setError("订单不存在");
            return false;
        }
        //查询单据状态
        if ($order['orders_status'] == self::ORDERS_STATUS_REGULAR) {
            $this->setError("已完成单据不允许做操作");
            return false;
        }

        //判断锁版本是否正确
        if ($order['lock_version'] != $data['lock_version']) {
            $this->setError("更新内容不是最新版本");
            return false;
        }
        $data['lock_version'] = $data['lock_version'] + 1;
        Db::startTrans();

        try {
            $data['orders_id'] = $order['orders_id'];
            $this->exists();
            $data['user_id'] = $user['user_id'];
            if (!$this->save($data)) {
                Db::rollback();
                throw new Exception('更新销售单失败');
            }
            // 删除草稿单据
            $this->_delDetails($data);
            //创建当前时间
            $time_now = time();
            $data['details'] = json_decode($data['details'], true);
            if (!empty($data['details'])) {
                $this->_saveDetails($data, $time_now, $data['orders_id']);
            }
            //如果提交正式则执行扣款,和减少商品数量操作
            if ($data['orders_status'] == self::ORDERS_STATUS_REGULAR) {
                //更新库存和账户
                $this->updateStockMoney($data['com_id'], $data['orders_code']);
                //写入客户流水
                $this->RecordDiaryClient($data, 38);
            }
            if (!empty($data['attachment'])) {
                $attachModel = new AttachModel();
                // 如果已存在附件则删除
                $attachModel->where('orders_id', '=', $order['orders_id'])->delete();
                // 附件保存
                $attach = json_decode($data['attachment'], true);
                $row = [
                    'orders_id' => $order['orders_id'],
                    'url' => $attach['assist_url'],
                    'extension' => $attach['extension'],
                    'size' => $attach['size'],
                    'key' => $attach['key'],
                    'name' => $attach['name'],
                    'category' => $attachModel::ATTACHMENT_CATEGORY_ATTACH,
                    'create_time' => time(),
                    'com_id' => $data['com_id'],
                    'order_type' => 2,
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
            return false;
        }
    }

    /**
     * 添加销售单
     * @param $data array
     * @param $user
     * @return mixed
     */
    private function _insertSale($data = null, $user = null)
    {
        $data['orders_code'] = BuildCode::dateCode('SO');
        //创建当前时间
        $time_now = time();
        //开始添加
        Db::startTrans();
        try {
            //添加订单，如果添加失败，则rollback所有操作
            $data['user_id'] = $user['user_id'];
            if (!$this->save($data)) {
                throw new Exception('添加销售单失败');
            }
            // 查询订单id
            $exist = $this->getOne(['orders_code' => $data['orders_code']]);
            $order_id = $exist['orders_id'];
            $data['details'] = json_decode($data['details'], true);
            if (!empty($data['details'])) {
                $this->_saveDetails($data, $time_now, $order_id);
            }
            //如果提交正式则执行扣款,和减少商品数量操作
            if ($data['orders_status'] == self::ORDERS_STATUS_REGULAR) {
                //更新库存和账户
                $this->updateStockMoney($data['com_id'], $data['orders_code']);
                //写入客户流水
                $this->RecordDiaryClient($data, 38);
            }
            if (!empty($data['attachment'])) {
                $attachModel = new AttachModel();
                // 如果已存在附件则删除
                $attachModel->where('orders_id', '=', $order_id)->delete();
                // 附件保存
                $attach = json_decode($data['attachment'], true);
                $row = [
                    'orders_id' => $order_id,
                    'url' => $attach['assist_url'],
                    'extension' => $attach['extension'],
                    'size' => $attach['size'],
                    'key' => $attach['key'],
                    'name' => $attach['name'],
                    'category' => $attachModel::ATTACHMENT_CATEGORY_ATTACH,
                    'create_time' => time(),
                    'com_id' => $data['com_id'],
                    'order_type' => 2,
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
            return false;
        }
    }

    /**
     * 保存销售单子
     * @param $data
     * @param $time_now
     * @param $order_id
     * @throws Exception
     */
    private function _saveDetails($data, $time_now, $order_id)
    {
        //循环取出details中的数据
        $details = [];
        foreach ($data['details'] as $item) {
            $details[] = [
                'orders_id' => $order_id,
                'orders_code' => $data['orders_code'],
                'goods_code' => $item['goods_code'],
                'goods_id' => $item['goods_id'],
                'color_id' => $item['color_id'],
                'size_id' => $item['size_id'],
                'goods_number' => $item['goods_number'],
                'goods_price' => $item['goods_price'],
                'goods_tmoney' => $item['goods_tmoney'],
                'com_id' => $data['com_id'],
                'create_time' => $time_now,
            ];
        }
        $detailModel = new SaleOrdersDetailsModel();
        $res = $detailModel->insertAll($details);
        if (!$res) {
            Db::rollback();
            throw new Exception("添加销售单详情失败");
        }
    }

    /**
     * 删除销售单
     * @param null $data
     * @throws Exception
     */
    private function _delDetails($data = null)
    {
        $detailModel = new SaleOrdersDetailsModel();
        //删除此单据对应的商品详情
        $where['orders_code'] = $data['orders_code'];
        $where['com_id'] = $data['com_id'];
        $detail_del = $detailModel->where($where)->delete();
        if (!$detail_del) {
            Db::rollback();
            throw new Exception("删除失败");
        }
    }

    /**
     * Notes:提交正式后更新客户账户余额，库存
     * User:ccl
     * DateTime:2021/1/26 15:34
     * @param $data
     */
    public function updateStockMoney($com_id, $orders_code)
    {
        $details_info = $this->getOrdersDetails(['com_id' => $com_id, 'orders_code' => $orders_code]);
        if (!$details_info) {
            throw new Exception('(更新库存账户金额)获取销售单详情失败');
        }
        //更新库存
        $this->baseSaveOrdersToStock($details_info);
        //更新收款账户金额
        $this->UpdatePayeeAmount($details_info['settlement_id'], $details_info['orders_rmoney'], $details_info['com_id']);
        //更新支付账户金额
        $this->UpdatePayAmount($details_info['client_id'], $details_info['orders_rmoney'], $details_info['com_id']);
    }

    /**
     * Notes:更新商品库存
     * User:ccl
     * DateTime:2021/1/26 16:55
     * @param $detail_info
     * @throws \think\Exception
     */
    public function baseSaveOrdersToStock($detail_info)
    {
        foreach ($detail_info['details'] as $item) {
            $data = $item;
            $data['warehouse_id'] = $detail_info['warehouse_id'];
            $data['orders_code'] = $detail_info['orders_code'];
            $data['stock_number'] = $data['goods_number'] * -1;
            $goods_stock = new GoodsStockModel();
            $goods_stock->updateOrInsert($data, '销售单');
        }
    }

    /**
     * Notes:更新收款账户金额(增加)
     * User:ccl
     * DateTime:2021/1/26 17:01
     */
    public function UpdatePayeeAmount($settlement_id, $money, $com_id)
    {
        $settlement = new SettlementModel();
        $data = $settlement->where(['settlement_id' => $settlement_id, 'com_id' => $com_id])->field('settlement_money,lock_version')->find();
        if (!$data) {
            throw new Exception('收款账户余额查询失败');
        }
        $settlement_money = bcadd($data['settlement_money'], $money, 2);
        $lock_version = $data['lock_version'] + 1;
        $res = $settlement->where(['settlement_id' => $settlement_id, 'com_id' => $com_id])->update(['settlement_money' => $settlement_money, 'lock_version' => $lock_version]);
        if (!$res) {
            throw new Exception('收款账户进账失败');
        }
    }

    /**
     * Notes:更新支付账户金额(减少)
     * User:ccl
     * DateTime:2021/1/26 17:28
     */
    public function UpdatePayAmount($client_id, $money, $com_id)
    {
        $client_account = new ClientAccountModel();
        $data = $client_account->where(['client_id' => $client_id, 'com_id' => $com_id])->field('account_money,lock_version')->find();
        if (!$data) {
            throw new Exception('支付账户余额查询失败');
        }
        $account_money = bcsub($data['account_money'], $money, 2);
        $lock_version = $data['lock_version'] + 1;
        $res = $client_account->where(['client_id' => $client_id, 'com_id' => $com_id])->update(['account_money' => $account_money, 'lock_version' => $lock_version]);
        if (!$res) {
            throw new Exception('支付账户扣款失败');
        }
    }

    public function getOrdersDetails($where)
    {
        return $this->alias('so')
            ->with('details')
            ->join('hr_client_base cb', 'so.client_id=cb.client_id', 'left')
            ->join('hr_user user', 'so.salesman_id=user.user_id', 'left')
            ->join('hr_dict d', 'so.delivery_id=d.dict_id', 'left')
            ->join('hr_settlement s', 'so.settlement_id=s.settlement_id', 'left')
            ->join('hr_organization o', 'so.warehouse_id=o.org_id', 'left')
            ->where('so.com_id', $where['com_id'])
            ->where('so.orders_code', $where['orders_code'])
            ->field('so.*,cb.client_name,user.user_name,d.dict_name,s.settlement_name,o.org_name')
            ->find();
    }

    public function RecordDiaryClient($data, $type)
    {
        $diary_client = new DiaryClientModel();
        $client_account = new ClientAccountModel();
        $sttlement = new SettlementModel();
        //获取单据信息
        $order_info = $this->getOne(['com_id' => $data['com_id'], 'orders_code' => $data['orders_code']]);
        if (!$order_info) {
            throw new Exception('(写入客户流水)获取单据信息失败');
        }
        //获取客户账户余额
        $account_info = $client_account->getOne(['com_id' => $order_info['com_id'], 'client_id' => $order_info['client_id']]);
        if (!$account_info) {
            throw new Exception('(写入客户流水)获取客户余额失败');
        }
        //获取结算账户余额
        $sttlement_money = $sttlement->getOne(['com_id' => $order_info['com_id'], 'settlement_id' => $order_info['settlement_id']], 'settlement_money');
        if (!$sttlement_money) {
            throw new Exception('(写入客户流水)获取结算账户余额失败');
        }
        //账户余额加订单应收金额
        $pbalance = bcadd($account_info['account_money'], $order_info['orders_pmoney'], 2);
        $data = [
            'client_id' => $order_info['client_id'],
            'orders_code' => $order_info['orders_code'],
            'user_id' => $order_info['user_id'],
            'settlement_id' => $order_info['settlement_id'],
            'account_id' => $type,
            'pmoney' => $order_info['orders_pmoney'],
            'rmoney' => $order_info['orders_rmoney'],
            'pbalance' => $pbalance,
            //结算账户余额
            'settlement_balance' => $sttlement_money['settlement_money'],
            'client_balance' => $account_info['account_money'],
            'create_time' => strtotime($order_info['create_time']),
            'remark' => $order_info['orders_remark'],
            'com_id' => $order_info['com_id']
        ];
        $res = $diary_client->addRecord($data);
        if (!$res) {
            throw new Exception('写入客户流水失败');
        }
    }

    /**
     * 得到销售日报
     * @param $range
     * @param $where
     * @return mixed
     */
    public function getSaleDaily($range, $where)
    {
        $sql_arr = [
            1 => "FROM_UNIXTIME(orders_date,'-%m-%d')", // 日
            2 => "'第',FROM_UNIXTIME(orders_date,'%V'),'周'", // 周
            3 => "'第',FROM_UNIXTIME(orders_date,'%m'),'月'", // 月
            4 => "'第',quarter(FROM_UNIXTIME(orders_date,'%Y-%m-%d')),'季度'", // 季
            5 => "'年'", // 年
        ];
        $str = $sql_arr[$range];
        $field = "CONCAT(YEAR(FROM_UNIXTIME(orders_date,'%Y-%m-%d')),{$str}) date,SUM(goods_number) goods_number,SUM(profit) profit, orders_pmoney,SUM(orders_pmoney) orders_pmoney";
        return $this->where('orders_status', 9)
            ->where($where)
            ->group('date')
            ->field($field)
            ->select()->toArray();
    }

    /**
     * 销售额
     * @param $com_id int
     * @return array
     */
    public function sale_volume(int $com_id): array
    {
        $start_today = strtotime(date('Y-m-d'));
        $status = self::ORDERS_STATUS_REGULAR;
        $today_sql = "select sum(orders_pmoney) as money from hr_sale_orders where orders_status = {$status} and orders_date >=  {$start_today}  and com_id = {$com_id}";
        $today_res = Db::query($today_sql);
        $start_yesterday = strtotime(date('Y-m-d', strtotime("-1 day")));
        $yesterday_sql = "select sum(orders_pmoney) as money from hr_sale_orders where orders_status = {$status} and orders_date >=  {$start_yesterday} and orders_date < {$start_today} and com_id = {$com_id}";
        $yester_res = Db::query($yesterday_sql);
        return [
            'yesterday' => $yester_res[0]['money'],
            'today' => $today_res[0]['money']
        ];
    }

}