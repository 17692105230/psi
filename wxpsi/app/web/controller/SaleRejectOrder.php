<?php
/**
 * 销售退货单
 */

namespace app\web\controller;

use app\common\model\web\Color as ColorModel;
use app\common\model\web\Goods as GoodsModel;
use app\common\model\web\SaleRejectOrdersDetails;
use app\common\utils\Compare;
use app\Request;
use app\web\model\SaleOrders;
use app\web\model\SalePlan as SalePlanModel;
use app\web\model\SalePlanDetails;
use app\web\model\SaleRejectOrders as SaleRejectOrdersModel;
use app\web\model\Size as SizeModel;
use app\web\validate\SaleRejectOrders as SaleRejectOrdersValidate;

class SaleRejectOrder extends Controller
{

    public function lists(Request $request)
    {
        $page = $request->get('page', 1);
        $rows = $request->get('rows', 20);
        $sale_orders = new SaleRejectOrdersModel();
        $where = [
            'com_id' => $this->com_id,
        ];
        $ordersStatus = $request->get("orders_status");
        $saleModel = new SaleOrders();
        if ($ordersStatus != "" && in_array($ordersStatus, [$saleModel::ORDERS_STATUS_REGULAR, $saleModel::ORDERS_STATUS_DRAFT])) {
            $where['orders_status'] = $ordersStatus;
        }
        $res = $sale_orders->loadData($this->getData($where), $page, $rows);
        if (!$res) {
            return $this->renderError($sale_orders->getError());
        }
        return $this->renderSuccess($res);
    }

    /**
     * 销售退货单详情
     * @param Request $request
     * @return array
     */
    public function orderDetails(Request $request)
    {
        $params = $request->get();
        $orders_code = $params['orders_code'];
        if (!$orders_code) {
            return $this->renderError("参数错误", 401);
        }
        // 查询销售单 orders
        $saleModel = new SaleRejectOrdersModel();
        $orders = $saleModel->getOne(['orders_code' => $orders_code]);
        $orderData = json_decode($orders, true);
        if (empty($orderData)) {
            return $this->renderError("数据不存在", 403);
        }
        // 查询details
        $saleDetailsModel = new SaleRejectOrdersDetails();
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
     * 保存销售退货单
     * @param Request $request
     * @return array
     */
    public function saveRejectOrders(Request $request)
    {
        $data = $request->post();
        $saleVilidate = new SaleRejectOrdersValidate();
        if (!$saleVilidate->scene('add')->check($data)) {
            return $this->renderError($saleVilidate->getError());
        }
        $sale_reject_orders = new SaleRejectOrdersModel();
        $user_info = $this->user;
        $data['user_id'] = $user_info['user_id'];
        $res = $sale_reject_orders->updateOrder($this->getData($data));
        if (!$res) {
            return $this->renderError($sale_reject_orders->getError());
        }
        $orders = $sale_reject_orders->getRejectOrderDetail($this->getData(['orders_code' => $res]));
        return $this->renderSuccess(['orders' => $orders], '保存成功');
    }

    /**
     * 删除销售退货单
     * @param Request $request
     * @return array
     */
    public function delRejectOrder(Request $request)
    {
        $data = $request->get();
        $saleVilidate = new SaleRejectOrdersValidate();
        if (!$saleVilidate->scene('del')->check($data)) {
            return $this->renderError($saleVilidate->getError());
        }
        $sale_reject_orders = new SaleRejectOrdersModel();
        $res = $sale_reject_orders->deleteRejectOrder($this->getData($data));
        if (!$res) {
            return $this->renderError($sale_reject_orders->getError());
        }
        return $this->renderSuccess([], '删除成功');
    }

    /**
     * Notes:撤销销售退货单
     * User:ccl
     * DateTime:2021/2/3 16:40
     * @param Request $request
     * @return array
     */
    public function saveRevokeRejectOrders(Request $request)
    {
        $data = $request->post();
        $saleVilidate = new SaleRejectOrdersValidate();
        if (!$saleVilidate->scene('repeal')->check($data)) {
            return $this->renderError($saleVilidate->getError());
        }

        $saleOrders = new SaleRejectOrdersModel();
        //获取销售单详情
        $where['orders_code'] = $data['orders_code'];
        $where['com_id'] = $this->com_id;
        $detail_info = $saleOrders->getRejectOrderDetail($where);
        if (!$detail_info) {
            return $this->renderError('(撤销单据)查询销售退货单详情失败');
        }
        $res = $saleOrders->revokeSaleRejectOrders($detail_info, $data['lock_version']);
        if (!$res) {
            return $this->renderError($saleOrders->getError());
        }
        $order_info = $saleOrders->getRejectOrderDetail($where);
        return $this->renderSuccess(['orders' => $order_info], '撤销销售单成功');
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
            // 为空则为添加
            $data['data_insert'] = $data['details'];
            $data['data_delete'] = json_encode($data_delete);
            $data['data_update'] = json_encode($data_update);
            return $data;
        }
        $detailsModel = new SaleRejectOrdersDetails();
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

}