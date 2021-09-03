<?php
/**
 * 销售单
 */

namespace app\web\controller;

use app\common\utils\Compare;
use app\Request;
use app\web\model\ClientBase as ClientBaseModel;
use app\web\model\Organization as OrganizationModel;
use app\web\model\SaleOrders;
use app\web\model\SaleOrdersDetails;
use app\web\model\SalePlan;
use app\web\model\SalePlan as SalePlanModel;
use app\web\model\SalePlanDetails;
use app\web\validate\Sale as SaleVilidate;
use app\web\validate\SaleOrders as SaleOrdersValidate;
use app\common\model\web\Color as ColorModel;
use app\web\model\Size as SizeModel;
use app\common\model\web\Goods as GoodsModel;
use app\web\model\DiaryClient as DiaryClientModel;
use app\web\model\Attachment as AttachModel;
use app\web\model\MemberBase as MemberBaseModel;


class Sale extends Controller
{
    /**
     * 销售单 (保存|修改)
     * @param Request $request
     * @return array
     */
    public function saveSaleOrders(Request $request)
    {
        $data = $request->post();
        $saleValidate = new SaleOrdersValidate();
        if (!$saleValidate->scene('add')->check($data)) {
            return $this->renderError($saleValidate->getError());
        }
        $data['com_id'] = $this->com_id;
        $data['orders_date'] = strtotime($data['orders_date']);
        $saleModel = new SaleOrders();
        $res = $saleModel->saveOrders($data, $this->user);
        if (!$res) {
            return $this->renderError($saleModel->getError());
        }
        return $this->renderSuccess('', '添加成功');
    }

    /**
     * 销售单 (列表)
     * @param Request $request
     * @return mixed
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSaleOrdersList(Request $request)
    {
        $saleModel = new SaleOrders();
        $where = [
            'com_id' => $this->com_id,
        ];
        $ordersStatus = $request->get("orders_status");
        if ($ordersStatus != "" && in_array($ordersStatus, [$saleModel::ORDERS_STATUS_REGULAR, $saleModel::ORDERS_STATUS_DRAFT])) {
            $where['orders_status'] = $ordersStatus;
        }
        // 查询销售单
        $data = $saleModel->easyPaginate($where);
        $rows = json_decode($data['rows'], true);
        if (empty($rows)) {
            return $this->renderSuccess();
        }
        // 获取客户ids
        $clientIds = array_unique(array_column($rows, 'client_id'));
        $clientModel = new ClientBaseModel();
        $clientFields = ['client_id', 'client_name'];
        $clientList = $clientModel->getListByWhere([], implode($clientIds, ','), $clientFields);
        $clientList = array_column($clientList, null, 'client_id');

        // 获取仓库
        $houseIds = array_unique(array_column($rows, 'warehouse_id'));
        $houseModel = new OrganizationModel();
        $houseFields = ['org_id', 'org_pid', 'org_name'];
        $houseList = $houseModel->getListByWhere([], implode($houseIds, ','), $houseFields);
        $houseList = array_column($houseList, null, 'org_id');

        // 获取会员ids
        $memberIds = array_unique(array_column($rows, 'member_id'));
        $memberModel = new MemberBaseModel();
        $memberList = $memberModel
            ->where("member_id","in",$memberIds)
            ->column('member_id,member_name',"member_id");

        foreach ($rows as &$v) {
            if ((!$v['client_id'] && !$v['member_id']) || !$v['warehouse_id']) {
                continue;
            }
            if($v['orders_type']==0){
                $v['client_info'] = $clientList[$v['client_id']];
            }else{
                $v['member_info'] = $memberList[$v['member_id']];
            }
            $v['house_info'] = $houseList[$v['warehouse_id']];
        }
        return $this->renderSuccess(['rows' => $rows, 'total' => $data['total']]);
    }

    /**
     * 销售单详情
     * @param Request $request
     * @return array
     */
    public function getSaleOrdersInfo(Request $request)
    {
        $params = $request->get();
        $ordersId = intval($params['orders_id']);
        if (!$ordersId) {
            return $this->renderError("参数错误", 401);
        }
        // 查询销售单 orders
        $saleModel = new SaleOrders();
        $orders = $saleModel->getOne(['orders_id' => $ordersId]);
        $orderData = json_decode($orders, true);
        if (empty($orderData)) {
            return $this->renderError("数据不存在", 403);
        }
        // 查询details
        $saleDetailsModel = new SaleOrdersDetails();
        $details = $saleDetailsModel->getList(['orders_id' => $ordersId]);
        $detailsData = json_decode($details, true);
        // 查询颜色
        $colorIds = array_unique(array_column($detailsData, 'color_id'));
        $colorModel = new ColorModel();
        $colorList = $colorModel->getListByWhereIn('color_id', implode(',', $colorIds), 'color_id, color_name');
        $colorData = array_column(json_decode($colorList, true), null, 'color_id');
        // 查询尺码
        $sizeIds = array_unique(array_column($detailsData, 'size_id'));
        $sizeModel = new SizeModel();
        $sizeList = $sizeModel->getListByWhereIn('size_id', implode(',', $sizeIds), 'size_id, size_name');
        $sizeData = array_column(json_decode($sizeList, true), null, 'size_id');
        // 查询商品名字
        $goodsId = array_unique(array_column($detailsData, 'goods_id'));
        $goodsModel = new GoodsModel();
        $goodsList = $goodsModel->getListByWhereIn('goods_id', implode(',', $goodsId), 'goods_id, goods_name');
        $goodsData = array_column(json_decode($goodsList, true), null, 'goods_id');
        // 同一个商品分类
        $result = $this->_dealDetailsData($detailsData, $colorData, $sizeData, $goodsData);
        $orders['details'] = $result;
        // 查询附件信息
        $attachModel = new AttachModel();
        $attach = json_decode($attachModel->where('orders_id', '=', $ordersId)->where('order_type', '=', 2)->order('id', 'DESC')->find(), true);
        if ($attach) {
            $attach['downlod_url'] = "http://" . $attach['url'];
        }
        $orders['attach'] = $attach;
        return $this->renderSuccess($orders);
    }

    /**
     * 删除销售单
     * @param Request $request
     * @return array
     */
    public function delSaleOrders(Request $request)
    {
        $ordersId = $request->param('orders_id');
        $lockVersion = $request->param('lock_version');
        if (!$ordersId) {
            return $this->renderError("参数错误", 403);
        }
        $saleOrdersModel = new SaleOrders();
        $ordersRow = $saleOrdersModel->getOne(['orders_id' => $ordersId, 'com_id' => $this->com_id]);
        if (empty($ordersRow)) {
            return $this->renderError("销售单不存在", 404);
        }
        $ordersInfo = $ordersRow->toArray();
        if ($ordersInfo['orders_status'] != $saleOrdersModel::ORDERS_STATUS_DRAFT) {
            return $this->renderError("该销售单不可删除", 405);
        }
        if ($ordersInfo['lock_version'] != $lockVersion) {
            return $this->renderError("销售单不是最新版本", 406);
        }
        // 删除销售单
        $ordersAffect = $saleOrdersModel->delByWhere(['orders_id' => $ordersId]);
        if (!$ordersAffect) {
            return $this->renderError("删除销售单失败", 408);
        }
        $saleOrdersDetailsModel = new SaleOrdersDetails();
        // 查询商品是否存在
        $detailsExist = $saleOrdersDetailsModel->getList(['orders_id' => $ordersId]);
        $details = $detailsExist->toArray();
        if ($details) {
            // 删除销售单商品
            $detailsAffect = $saleOrdersDetailsModel->delByWhere(['orders_id' => $ordersId]);
            if (!$detailsAffect) {
                return $this->renderError("删除销售单失败", 407);
            }
        }
        return $this->renderSuccess();
    }

    /**
     * 销售订单详情
     * @param Request $request
     * @return array
     */
    public function planDetails(Request $request)
    {
        $params = $request->get();
        $orders_code = $params['orders_code'];
        if (!$orders_code) {
            return $this->renderError("参数错误", 401);
        }
        // 查询销售单 orders
        $saleModel = new SalePlanModel();
        $orders = $saleModel->getOne(['orders_code' => $orders_code]);
        $orderData = json_decode($orders, true);
        if (empty($orderData)) {
            return $this->renderError("数据不存在", 403);
        }
        // 查询details
        $saleDetailsModel = new SalePlanDetails();
        $details = $saleDetailsModel->getList(['orders_code' => $orders_code]);
        $detailsData = json_decode($details, true);
        // 查询颜色
        $colorIds = array_unique(array_column($detailsData, 'color_id'));
        $colorModel = new ColorModel();
        $colorList = $colorModel->getListByWhereIn('color_id', implode(',', $colorIds), 'color_id, color_name');
        $colorData = array_column(json_decode($colorList, true), null, 'color_id');
        // 查询尺码
        $sizeIds = array_unique(array_column($detailsData, 'size_id'));
        $sizeModel = new SizeModel();
        $sizeList = $sizeModel->getListByWhereIn('size_id', implode(',', $sizeIds), 'size_id, size_name');
        $sizeData = array_column(json_decode($sizeList, true), null, 'size_id');
        // 查询商品名字
        $goodsId = array_unique(array_column($detailsData, 'goods_id'));
        $goodsModel = new GoodsModel();
        $goodsList = $goodsModel->getListByWhereIn('goods_id', implode(',', $goodsId), 'goods_id, goods_name');
        $goodsData = array_column(json_decode($goodsList, true), null, 'goods_id');
        // 同一个商品分类
        $result = $this->_dealDetailsData($detailsData, $colorData, $sizeData, $goodsData);
        $orders['details'] = $result;
        return $this->renderSuccess($orders);
    }

    /**
     * 保存销售订单
     * @param Request $request
     * @return array
     */
    public function savePlan(Request $request)
    {
        $data = $request->post();
        $saleVilidate = new SaleVilidate();
        if (!$saleVilidate->scene('update')->check($data)) {
            return $this->renderError($saleVilidate->getError());
        }
        $salePlane = new SalePlanModel();
        $data['user_id'] = $this->user['user_id'];
        $res = $salePlane->updatePlane($this->getData($data));
        if (!$res) {
            return $this->renderError($salePlane->getError());
        }
        $orders = $salePlane->getPlanDetails($this->getData(['orders_code' => $res]));
        return $this->renderSuccess(['orders' => $orders], '保存成功');
    }

    /**
     * 删除订单
     * @return array
     */
    public function delPlan()
    {
        $data = $this->request->get();
        $saleVilidate = new SaleVilidate();
        if (!$saleVilidate->scene('delete')->check($data)) {
            return $this->renderError($saleVilidate->getError());
        }
        $salePlane = new SalePlanModel();
        $res = $salePlane->delSalePlan($this->getData($data));
        if (!$res) {
            return $this->renderError($salePlane->getError());
        }
        return $this->renderSuccess([], '删除成功');
    }

    /**
     * 查询数据分类 insert|update|delete
     * @param $data
     * @return array $data
     */
    private function dataCategory($data)
    {
        $data_delete = [];
        $data_update = [];
        // 唯一性字段
        $orderCode = isset($data['orders_code']) ? $data['orders_code'] : '';
        if (!$orderCode) {
            if (empty($data['details'])) {
                return $this->renderError("请选择商品");
            }
            // 为空则为添加
            $data['data_insert'] = $data['details'];
            $data['data_delete'] = json_encode($data_delete);
            $data['data_update'] = json_encode($data_update);
            return $data;
        }
        $detailsModel = new SalePlanDetails();
        $originData = $detailsModel->getList(['orders_code' => $orderCode])->toArray();
        $map = ['goods_id', 'color_id', 'size_id'];
        $compare = new Compare($originData, json_decode($data['details'], true), $map);
        $compare->handle();
        $compare->getAllResult($addResult, $updateResult, $delResult);
        $data['data_insert'] = json_encode($addResult);
        $data['data_update'] = json_encode($updateResult);
        $data['data_delete'] = json_encode($delResult);
        return $data;
    }

    /**
     * 客户对账流水
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function clientDebt(Request $request)
    {
        $params = $request->get();
        $client_id = $params['client_id']; // 客户Id
        $orders_code = $params['orders_code']; // 单据编号
        $start_time = $params['start_date']; // 开始时间
        $end_time = $params['end_date']; // 结束时间
        $diaryClientModel = new DiaryClientModel();
        $where = [];
        if ($client_id) {
            $where['client_id'] = $client_id;
        }
        if ($orders_code) {
            $where['orders_code'] = $orders_code;
        }
        $result = $diaryClientModel->where($where)
            ->when($start_time && $end_time, function ($query) use ($start_time, $end_time) {
                $query->whereTime('create_time', 'between', [strtotime($start_time), strtotime($end_time) + 24 * 3600 - 1]);
            })->select()->toArray();
        return $this->renderSuccess($result);
    }

    /**
     * 销售订单列表
     * @param Request $request
     * @return array
     */
    public function planList(Request $request)
    {
        $page = $request->get('page', 1);
        $rows = $request->get('rows', 20);
        $where = $request->get();
        $sale_plan = new SalePlanModel();
        $res = $sale_plan->loadData($this->getData($where), $page, $rows);
        if (!$res) {
            return $this->renderError($sale_plan->getError());
        }
        return $this->renderSuccess($res);
    }


}