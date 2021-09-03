<?php


namespace app\web\controller;

use app\Request;
use app\web\model\GoodsStock;
use app\web\model\PurchaseOrders as PurchaseOrdersModel;
use app\web\model\Organization as OrganizationModel;
use app\web\model\Category as CategoryModel;
use app\web\model\PurchaseOrdersDetails as PurchaseOrdersDetailsModel;
use app\web\model\Goods as GoodsModel;
use app\web\model\GoodsAssist as GoodsAssistModel;
use app\web\model\SaleOrders;
use app\web\model\Dict as DictModel;
use app\web\model\SaleOrdersDetails;

class Statistics extends Controller
{

    /**
     * 获取仓库列表
     * @param Request $request
     * @return array
     */
    public function getHouseList(Request $request)
    {
        $organization = new OrganizationModel();
        if ($this->org_ids) {
            $where = [
                ['org_id', 'in', $this->org_ids]
            ];
        } else {
            $where = [
                ['com_id', '=', $this->com_id]
            ];
        }
        $result = $organization->getList($where);
        return $this->renderSuccess($result);
    }

    /**
     * 供应商
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function supplier(Request $request)
    {
        $params = $request->get();
        $request_org_id = json_decode($params['org_id'], true);    // 仓库Ids
        $org_id = empty($request_org_id) ? '' : implode(',', $request_org_id);
        $custom = intval($params['custom']);    // 自定义
        $scope = trim($params['scope']);      // 自定义范围
        $start = trim($params['start']);      // 开始日期
        $end = trim($params['end']);          // 结束日期
        $where = [
            'com_id' => $this->com_id,
        ];
        $purchaseModel = new PurchaseOrdersModel();
        if ($custom) {
            // 自定义
            if (!$start || !$end) {
                return $this->renderError("自定义参数错误");
            }
            $period_start = strtotime($start);
            $period_end = strtotime($end) + 24 * 3600 - 1;
            $list = json_decode($purchaseModel->where($where)
                ->where("orders_date", ">=", $period_start)
                ->where("orders_date", "<=", $period_end)
                ->whereIn("warehouse_id", $org_id)
                ->select(), true);
            $result = $this->_dealHouseData($list);
            return $this->renderSuccess($result);
        }
        // 非自定义(全部时间all 昨日yesterday 今日today 本周week 本月month 本季season 本年year)
        if (!$scope) {
            return $this->renderError("参数错误");
        }
        $purchaseModel = new PurchaseOrdersModel();
        $list = json_decode($purchaseModel->where($this->_getList($scope, $org_id))->select(), true);
        $result = $this->_dealHouseData($list);
        return $this->renderSuccess($result);
    }

    /**
     * 商品
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function goods(Request $request)
    {
        $params = $request->get();
        $request_org_id = json_decode($params['org_id'], true);    // 仓库Ids
        $org_id = empty($request_org_id) ? '' : implode(',', $request_org_id);
        $category = intval($params['category']);  // 商品分类
        $custom = intval($params['custom']);    // 自定义
        $scope = trim($params['scope']);      // 自定义范围
        $start = trim($params['start']);      // 开始日期
        $end = trim($params['end']);          // 结束日期
        $where = [
            'com_id' => $this->com_id,
        ];
        $purchaseModel = new PurchaseOrdersModel();
        if ($custom) {
            // 自定义
            if (!$start || !$end) {
                return $this->renderError("自定义参数错误");
            }
            $period_start = strtotime($start);
            $period_end = strtotime($end) + 24 * 3600 - 1;
            $list = json_decode($purchaseModel->where($where)
                ->where("orders_date", ">=", $period_start)
                ->where("orders_date", "<=", $period_end)
                ->whereIn("warehouse_id", $org_id)
                ->select(), true);
            $chartData = $this->_chartData($list, $category);
            $houseData = $this->_dealHouseData($list);
            return $this->renderSuccess(['chart' => $chartData, 'house' => $houseData]);
        }
        // 非自定义(全部时间all 昨日yesterday 今日today 本周week 本月month 本季season 本年year)
        if (!$scope) {
            return $this->renderError("参数错误");
        }
        $purchaseModel = new PurchaseOrdersModel();
        $list = json_decode($purchaseModel->where($this->_getList($scope, $org_id))->select(), true);
        $chartData = $this->_chartData($list, $category);
        $houseData = $this->_dealHouseData($list);
        return $this->renderSuccess(['chart' => $chartData, 'house' => $houseData]);
    }

    /**
     * 获取图表信息
     * @param $data
     * @param $category
     * @return array
     */
    private function _chartData($data, $category)
    {
        // 查询分类
        $categoryModel = new CategoryModel();
        $where = ['com_id' => $this->com_id];
        $categoryList = array_column(json_decode($categoryModel->getList([]), true),
            null, 'category_id');

        $purchaseOrdersDetailsModel = new PurchaseOrdersDetailsModel();
        $goodsModel = new GoodsModel();
        // 获取订单id
        $orders_ids = array_column($data, 'orders_id');
        // 查询订单详情
        $details = json_decode($purchaseOrdersDetailsModel->getListByWhereIn("orders_id",
            implode(',', $orders_ids)), true);
        // 查询商品分类信息
        $goods_ids = array_column($details, 'goods_id');

        $goods = array_column(json_decode($goodsModel->getListByWhereIn("goods_id",
            implode(',', $goods_ids)), true), null, 'goods_id');
        $result = [];

        foreach ($details as $v) {
            $goods_id = $v['goods_id'];
            if ($category) {
                // 商品
                if (isset($result[$goods_id])) {
                    $result[$goods_id] = [
                        'name' => $goods[$goods_id]['goods_name'],
                        'value' => $result[$goods_id]['value'] + $v['goods_tmoney'],
                    ];
                    continue;
                }
                if (!isset($goods[$goods_id])) {
                    continue;
                }
                $result[$goods_id] = [
                    'name' => $goods[$goods_id]['goods_name'],
                    'value' => $v['goods_tmoney'],
                ];
            } else {
                // 分类
                if (!isset($goods[$goods_id])) {
                    continue;
                }
                $category_id = $goods[$goods_id]['category_id'];
                if (isset($result[$category_id])) {
                    $result[$category_id] = [
                        'name' => $categoryList[$category_id]['category_name'],
                        'value' => $result[$category_id]['value'] + $v['goods_tmoney'],
                    ];
                    continue;
                }
                if (!isset($categoryList[$category_id])) {
                    continue;
                }
                $result[$category_id] = [
                    'name' => $categoryList[$category_id]['category_name'],
                    'value' => $v['goods_tmoney'],
                ];
            }

        }
        return $result;
    }

    /**
     * 根据条件获取where
     * @param $condition
     * @param $org_id
     * @return array|array[]
     */
    private function _getList($condition, $org_id = null)
    {
        $where = [];
        switch ($condition) {
            case "yesterday":
                // 昨天
                $period_start = strtotime(date("Y-m-d", strtotime("-1 day")));
                $period_end = $period_start + 24 * 3600 - 1;
                $where = [
                    ["orders_date", '>=', $period_start],
                    ["orders_date", '<=', $period_end]
                ];
                break;
            case "today":
                // 今天
                $period_start = strtotime(date("Y-m-d"));
                $where = [
                    ['orders_date', ">=", $period_start]
                ];
                break;
            case "week":
                // 本周
                $w = date('w', time());
                $period_start = strtotime(date('Y-m-d', strtotime("-" . ($w ? $w - 1 : 6) . ' days')));
                $where = [
                    ['orders_date', '>=', $period_start]
                ];
                break;
            case "month":
                // 本月
                $period_start = strtotime(date('Y-m-01'));
                $where = [
                    ['orders_date', '>=', $period_start]
                ];
                break;
            case "season":
                // 本季
                $season = ceil((date('n')) / 3); // 当月是第几季度
                $period_start = mktime(0, 0, 0, $season * 3 - 3 + 1, 1, date('Y'));
                $where = [
                    ['orders_date', '>=', $period_start]
                ];
                break;
            case "year":
                // 本年
                $period_start = strtotime(date("Y-01-01"));
                $where = [
                    ['orders_date', '>=', $period_start],
                ];
                break;
            default:
                break;
        }
        if ($org_id) {
            array_push($where, ['warehouse_id', 'in', $org_id]);
        }
        return $where;
    }

    /**
     * 处理仓库数据
     * @param $data
     * @return array
     */
    private function _dealHouseData($data)
    {
        $organModel = new OrganizationModel();
        // 查询仓库信息
        $org_ids = array_unique(array_column($data, 'warehouse_id'));
        $orgList = json_decode($organModel->getListByWhereIn("org_id", implode(',', $org_ids)), true);
        $houseList = array_column($orgList, null, 'org_id');
        $result = [];
        foreach ($data as $v) {
            $warehouse_id = $v['warehouse_id'];
            $debt_money = $v['orders_pmoney'] - $v['orders_rmoney'];    //应付欠款=应付金额-实付金额
            if (isset($result[$warehouse_id])) {
                $house = $result[$warehouse_id];
                $result[$warehouse_id] = [
                    'org_name' => $houseList[$warehouse_id]['org_name'],
                    'goods_number' => $house['goods_number'] + $v['goods_number'],
                    'orders_rmoney' => $house['orders_rmoney'] + $v['orders_rmoney'],
                    'debt_money' => $house['debt_money'] + $debt_money,
                ];
                continue;
            }
            $result[$warehouse_id] = [
                'org_name' => $houseList[$warehouse_id]['org_name'],
                'goods_number' => $v['goods_number'],
                'orders_rmoney' => $v['orders_rmoney'],
                'debt_money' => $debt_money,
            ];
        }
        return $result;
    }

    /**
     * 销售日报
     */
    public function salesDaily(Request $request)
    {
        $params = $request->get();
        $custom = intval($params['custom']);  // 自定义
        $scope = trim($params['scope']);      // 自定义范围
        $start = trim($params['start']);      // 开始日期
        $end = trim($params['end']);          // 结束日期
        $range = intval($params['range']);    // 范围(1日 2周 3月 4季 5年)
        $saleModel = new SaleOrders();
        $where = null;
        if ($custom) {
            $period_start = strtotime($start); // 开始时间
            $period_end = strtotime($end) + 24 * 3600 - 1; // 结束时间
            $where = [
                ['orders_date', '>=', $period_start],
                ['orders_date', '<=', $period_end]
            ];
        } else {
            $where = $this->_getList($scope); // 获取条件
        }
        array_push($where, ['com_id', '=', $this->com_id]);
        if ($this->org_ids) {
            array_push($where, ['warehouse_id', 'in', $this->org_ids]);
        }
        $list = $saleModel->getSaleDaily($range, $where);
        return $this->renderSuccess($list);
    }

    /**
     * 库存分析
     */
    public function stockAnalysis(Request $request)
    {
        $params = $request->get();
        $category = $params['category'] ?: 0; // 0 店仓分析 1 商品分析
        $where = [
            ['com_id', '=', $this->com_id]
        ];
        if ($this->org_ids) {
            array_push($where, ['warehouse_id', 'in', $this->org_ids]);
        }
        // 仓库数据列表
        $stockModel = new GoodsStock();
        $stock_list = $stockModel->getList($where)->toArray();
        // 商品价格信息
        $goods_ids = array_column($stock_list, 'goods_id');
        $goodsModel = new GoodsModel();
        $goods_list = array_column($goodsModel->getListByWhereIn('goods_id', $goods_ids)->toArray(), null, 'goods_id');
        // 获取仓库列表
        $orModel = new OrganizationModel();
        $house_list = array_column($orModel->getList(['com_id' => $this->com_id])->toArray(), null, 'org_id');
        $result = [];
        if ($category) {
            // 商品分析
            foreach ($goods_list as $goods) {
                $goods_id = $goods['goods_id'];
                // 查询出有该商品得库存
                $stock_goods = array_map(function ($stock) use ($goods_id) {
                    if ($stock['goods_id'] == $goods_id) {
                        return $stock;
                    }
                }, $stock_list);
                // 计算该商品的金额
                $num = array_sum(array_column($stock_goods, 'stock_number')); // 库存数量
                $money = $num * $goods['goods_rprice']; // 金额
                array_push($result, [
                    'name' => $goods['goods_name'],
                    'num' => $num,
                    'money' => $money,
                ]);
            }
        } else {
            // 店仓分析
            $warehouse_list = array_filter(array_unique(array_column($stock_list, 'warehouse_id')));
            foreach ($warehouse_list as $warehouse_id) {
                // 查询出有该商品得库存
                $stock_goods = array_map(function ($stock) use ($warehouse_id) {
                    if ($stock['warehouse_id'] == $warehouse_id) {
                        return $stock;
                    }
                }, $stock_list);
                // 计算商品金额
                $num = 0;   // 数量
                $money = 0.00; // 金额
                foreach ($stock_goods as $goods) {
                    $goods_id = $goods['goods_id'];
                    if (isset($goods_list[$goods_id])) {
                        $num += $goods['stock_number'];
                        $money += $goods['stock_number'] * $goods_list[$goods_id]['goods_rprice'];
                    }
                }
                array_push($result, [
                    'name' => $house_list[$warehouse_id]['org_name'],
                    'num' => $num,
                    'money' => $money,
                ]);
            }
        }
        return $this->renderSuccess($result);
    }

    /**
     * 库存查询
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function stockQuery(Request $request)
    {
        $params = $request->get();
        $str = trim($params['str']); // 商品名称 编号 助记符等
        $warehouse = json_decode($params['warehouse'], true); // 店仓
        $goods = json_decode($params['goods'], true); // 商品分类
        $sort = intval($params['sort']); // 排序 (1 库存数量 从高到低 2 库存数量 从低到高 3 创建商品时间 从高到低 4 创建商品时间 从低到高)
        $zero = intval($params['zero']); // 零库存商品 -1不显示 1显示
        $below = intval($params['below']); // 负库存商品 -1不显示 1显示
        $use = intval($params['use']); // 可用库存 -1不显示 1显示
        $stop = intval($params['stop']); // 停用商品 -1 不显示 1显示
        $ids1 = [];
        $ids2 = [];
        $goods_where = [
            ['com_id', '=', $this->com_id]
        ];
        if ($stop < 0) {
            // 停用商品不显示
            array_push($goods_where, ['goods_status', '=', 1]);
        }
        if ($goods) {
            // 根据分类查询商品id
            $goodsModel = new GoodsModel();
            $ids1 = array_column(json_decode($goodsModel->where(array_merge([['category_id', 'in', $goods]], $goods_where))->select(), true), 'goods_id');
            if (!$ids1) {
                return $this->renderSuccess();
            }
        }
        if ($str) {
            // 商品名称 编号 助记符等
            $goodsModel = new GoodsModel();
            $ids2 = array_column(json_decode($goodsModel->where($goods_where)->where(function ($query) use ($str) {
                $query->whereOr('goods_name', 'like', "%{$str}%");
                $query->whereOr('goods_code', 'like', "%{$str}%");
                $query->whereOr('goods_serial', 'like', "%{$str}%");
                $query->whereOr('goods_barcode', 'like', "%{$str}%");
            })
                ->select(), true), 'goods_id');
            if (empty($ids1) && empty($ids2)) {
                return $this->renderSuccess();
            }
        }
        if ($ids1 && $ids2) {
            // 都存在则取查询交集
            $goods_ids = array_intersect($ids1, $ids2);
        } else {
            // 否则取查询条件存在的值
            $goods_ids = $ids1 ? $ids1 : $ids2;
        }

        $where = [
            ['com_id', '=', $this->com_id]
        ];
        if ($zero < 0) {
            // 不显示零库存
            array_push($where, ['stock_number', '<>', 0]);
        }
        if ($below < 0) {
            // 不显示负库存商品
            array_push($where, ['stock_number', '>=', 0]);
        }
        if ($use < 0) {
            // 不显示不可用库存
            array_push($where, ['stock_number', '>', 0]);
        }
        if ($this->org_ids) {
            array_push($where, ['warehouse_id', 'in', $this->org_ids]);
        }
        // 根据条件查询库存
        $stockModel = new GoodsStock();
        $stock_list = $stockModel->where($where)
            ->when($goods_ids, function ($query) use ($goods_ids) {
                $query->where('goods_id', 'in', $goods_ids);
            })
            ->when($warehouse, function ($query) use ($warehouse) {
                $query->where('warehouse_id', 'in', $warehouse);
            })
            ->select()->toArray();
        $stock_goods_ids = array_column($stock_list, 'goods_id');
        // 根据库存获取商品信息
        $goodsModel = new GoodsModel();
        $list = json_decode($goodsModel->getListByWhereIn('goods_id', $stock_goods_ids), true);
        // 获取商品信息
        $goodsAssistModel = new GoodsAssistModel();
        $assist_list = array_column(json_decode($goodsAssistModel->where([
            ['goods_id', 'in', $stock_goods_ids],
            ['assist_sort', '=', 0]
        ])->select(), true), null, 'goods_id');
        foreach ($list as &$v) {
            $goods_id = $v['goods_id'];
            $arr = array_map(function ($v) use ($goods_id) {
                if ($goods_id == $v['goods_id']) {
                    return $v;
                }
            }, $stock_list);
            $stock = array_sum(array_column($arr, 'stock_number')); // 库存
            $assist = $assist_list[$goods_id]; // 附件
            $v['stock_number'] = $stock;
            $v['assist_url'] = "http://" . $assist['assist_url'];
            $v['create_time'] = strtotime($v['create_time']);
        }
        $create_time = array_column($list, 'create_time');
        $stocks = array_column($list, 'stock_number');
        switch ($sort) {
            case 1: // 库存数量 从高到低
                array_multisort($stocks, SORT_DESC, $list);
                break;
            case 2: // 库存数量 从低到高
                array_multisort($stocks, SORT_ASC, $list);
                break;
            case 3: // 创建商品时间 从高到低
                array_multisort($create_time, SORT_DESC, $list);
                break;
            case 4: // 创建商品时间 从低到高
                array_multisort($create_time, SORT_ASC, $list);
                break;
        }
        return $this->renderSuccess($list);
    }

    /**
     * 出库单历史详情
     * @param Request $request
     * @return array
     */
    public function goodsStockDetails(Request $request)
    {
        $params = $request->get();
        $goods_id = intval($params['goods_id']); // 商品id
        $scope = $params['scope']; // 时间范围 week 本周 month 本月
        $start = $params['start']; // 开始时间
        $warehouse_id = intval($params['warehouse_id']); // 仓库id
        $end = $params['end']; // 结束时间
        if (!$goods_id) {
            return $this->renderError("商品id不存在");
        }
        // 查询商品信息
        $goodsModel = new GoodsModel();
        $goods_detail = json_decode($goodsModel->getOne(['goods_id' => $goods_id]), true);
        if (!$goods_detail) {
            return $this->renderError("商品不存在");
        }
        // 查询单位
        $dictModel = new DictModel();
        $dict = $dictModel->getOne(['dict_type' => "unit", 'dict_id' => $goods_detail['unit_id']]);

        $where = [];
        // 自定义
        if ($start && $end) {
            $period_start = strtotime($start);
            $period_end = strtotime($end) + 24 * 3600 - 1;
            $where = [
                ['orders_date', '>=', $period_start],
                ['orders_date', '<=', $period_end]
            ];
        } else {
            $where = $this->_getList($scope);
        }
        // 查询附件
        $goodsAssistModel = new GoodsAssistModel();
        $assist = json_decode($goodsAssistModel->getOne(['goods_id' => $goods_id, 'assist_sort' => 0]), true);
        // 查询 期初库存 总(入库数 出库数 期末库存)
        $purchase = $this->_getTotalEnter($goods_id, $where, $warehouse_id);
        $sale = $this->_getTotalOut($goods_id, $where, $warehouse_id);
        $total_enter = $purchase['total']; // 总入库数
        $total_out = $sale['total']; // 总出库数
        // 期末库存
        $goodsStock = new GoodsStock();
        $stock_where = ['goods_id' => $goods_id];
        if ($warehouse_id) {
            $stock_where['warehouse_id'] = $warehouse_id;
        }
        $nowStock = array_sum(array_column(json_decode($goodsStock->getList($stock_where), true), 'stock_number'));
        $list = array_merge($purchase['list'], $sale['list']);
        array_multisort(array_column($list, 'time'), SORT_DESC, $list);
        $result = [];
        foreach ($list as $v) {
            if (!$v['goods_number']) {
                continue;
            }
            $date = explode(' ', $v['time'])[0];
            if (isset($result[$date])) {
                array_push($result[$date], $v);
            } else {
                $result[$date] = [$v];
            }
        }
        $assist['assist_url'] = "http://" . $assist['assist_url'];
        $data = [
            'list' => $result,
            'goods' => $goods_detail,
            'assist' => $assist,
            'nowStock' => intval($nowStock),
            'total_enter' => intval($total_enter),
            'total_out' => intval($total_out),
            'dict' => $dict,
        ];
        return $this->renderSuccess($data);
    }

    /**
     * @param $goods_id
     * @param $where
     * @param int $warehouse_id
     * @return int|mixed
     */
    private function _getTotalOut($goods_id, $where, $warehouse_id = 0)
    {
        $saleOrdersDetailsModel = new SaleOrdersDetails();
        $saleOrdersDetailsList = json_decode($saleOrdersDetailsModel->getList(['goods_id' => $goods_id]), true);
        $sale_order_ids = array_unique(array_column($saleOrdersDetailsList, 'orders_id'));
        $saleOrderModel = new SaleOrders();
        $arr = [
            ['orders_id', 'in', $sale_order_ids],
            ['orders_status', '=', 9],
        ];
        if ($warehouse_id) {
            array_push($arr, ['warehouse_id', '=', $warehouse_id]);
        }
        $saleList = array_column(json_decode($saleOrderModel->getList(array_merge($arr, $where)), true), null, 'orders_id');
        $total_out = 0; // 总出库数
        $result = [];
        foreach ($saleOrdersDetailsList as $details) {
            $orders_id = $details['orders_id'];
            if (isset($saleList[$orders_id])) {
                $total_out += $details['goods_number'];
                if (isset($result[$details['orders_code']])) {
                    $result[$details['orders_code']]['goods_number'] += $details['goods_number'];
                    continue;
                }
                $result[$details['orders_code']] = [
                    'name' => '出货单',
                    'orders_code' => $details['orders_code'],
                    'goods_number' => $details['goods_number'],
                    'time' => $details['create_time']
                ];
            }
        }
        return ['total' => $total_out, 'list' => $result];
    }

    /**
     * @param $goods_id
     * @param $where
     * @param int $warehouse_id
     * @return int|mixed
     */
    private function _getTotalEnter($goods_id, $where, $warehouse_id = 0)
    {
        $purchaseOrdersDetailsModel = new PurchaseOrdersDetailsModel();
        $purchaseOrdersDetailsList = json_decode($purchaseOrdersDetailsModel->getList(['goods_id' => $goods_id]), true);
        $order_ids = array_unique(array_column($purchaseOrdersDetailsList, 'orders_id'));
        $purchaseOrderModel = new PurchaseOrdersModel();
        $arr = [
            ['orders_id', 'in', $order_ids],
            ['orders_status', '=', 9],
        ];
        if ($warehouse_id) {
            array_push($arr, ['warehouse_id', '=', $warehouse_id]);
        }
        $purchaseList = array_column(json_decode($purchaseOrderModel->getList(array_merge($arr, $where)), true), null, 'orders_id');
        $total_enter = 0; // 总入库数
        $result = [];
        foreach ($purchaseOrdersDetailsList as $details) {
            $orders_id = $details['orders_id'];
            if (isset($purchaseList[$orders_id])) {
                $total_enter += $details['goods_number'];
                if (isset($result[$details['orders_code']])) {
                    $result[$details['orders_code']]['goods_number'] += $details['goods_number'];
                    continue;
                }
                $result[$details['orders_code']] = [
                    'name' => '进货单',
                    'orders_code' => $details['orders_code'],
                    'goods_number' => $details['goods_number'],
                    'time' => $details['create_time']
                ];

            }
        }
        return ['total' => $total_enter, 'list' => $result];
    }


}