<?php
/**
 * Created by PhpStorm.
 * User: zll
 * Date: 2021/5/19
 * Time: 11:57
 */

namespace app\common\model\web;


use app\common\model\BaseModel;
use app\common\utils\BuildCode;
use think\Exception;
use app\web\model\InventoryOrdersDetails as InventoryOrdersDetailsModel;
use app\web\model\InventoryOrdersDetailsC as InventoryOrdersDetailsCModel;
use app\web\model\Goods as GoodsModel;
use app\web\model\GoodsStock as GoodsStockModel;

class InventoryOrders extends BaseModel
{
    protected $table = "hr_inventory_orders";
    protected $pk = "orders_id";

    //关联子单
    public function childrens()
    {
        return $this->hasMany(InventoryOrdersChildren::class, "orders_code", "orders_code");
    }

    //关联草稿详情
    public function details()
    {
        return $this->hasMany(InventoryOrdersDetails::class, "orders_code", "orders_code");
    }

    //关联正式详情
    public function detailsc()
    {
        return $this->hasMany(InventoryOrdersDetailsC::class, "orders_code", "orders_code");
    }

    //关联仓库
    public function organization()
    {
        return $this->belongsTo(Organization::class, "warehouse_id", "org_id");
    }

    //关联制单员
    public function user()
    {
        return $this->hasOne(User::class, "user_id", "user_id");
    }

    //优化的 根据单据id获取单据详情
    public function getByOrdersId_($orders_id)
    {
        $goodModel = new GoodsModel();                      //商品模型
        $gdModle = new GoodsDetails();                      //商品详情模型
        $colorModel = new Color();                          //颜色模型
        $sizeModel = new Size();                            //尺码模型
        $goodsStockModel = new GoodsStockModel();           //库存模型
        $detailModel = new InventoryOrdersDetails();        //盘点单详情模型-草稿
        $childModel = new InventoryOrdersChildren();        //子单据模型

        //获取单据基础数据
        $orderData = $this
            ->where("orders_id", "=", $orders_id)
            ->with(['organization', 'user'])
            ->find();
        if(!$orderData){
            $this->error = "盘点单不存在";
            return false;
        }

        //如果单据已完成
        if ($orderData['orders_status'] == 9) {
            $detailModel = new InventoryOrdersDetailsC();       //盘点单详情模型-完成
        }

        //获取子单列表
        $childList = $childModel->where("orders_id","=",$orders_id)->column("*","children_code");

        //获取单据详情列表
        $detailList = $detailModel->where("orders_id", "=", $orders_id)->select()->toArray();

        //获取相关商品列表
        $goods_ids = array_values(array_unique(array_column($detailList, "goods_id")));
        $goodsListData = $goodModel->with(['images'])->where("goods_id", "in", $goods_ids)->select()->toArray();
        $goodsList = [];
        foreach ($goodsListData as $v) {
            $goodsList[$v['goods_id']] = $v;
        }
        //获取所有相关颜色
        $color_ids = array_values(array_unique(array_column($detailList, "color_id")));
        $colorList = $colorModel->where("color_id", "in", $color_ids)->column("*", "color_id");

        //获取所有相关尺码
        $size_ids = array_values(array_unique(array_column($detailList, "size_id")));
        $sizeList = $sizeModel->where("size_id", "in", $size_ids)->column("*", "size_id");

        //获取库存列表数据,并且加工一下
        $goodsStockList = $goodsStockModel
            ->where("warehouse_id", "=", $orderData['warehouse_id'])
            ->where("goods_id", "in", $goods_ids)
            ->select();
        $stockData = getStockGoodsColorSize($goodsStockList);

        $res = [];
        foreach ($detailList as $item) {
            //判断子单是否存在
            if(isset($res[$item['children_code']])){//存在子单
                //判断是否存在商品
                if (isset($res[$item['children_code']]['list'][$item['goods_id']])) {
                    $goods = $res[$item['children_code']]['list'][$item['goods_id']];
                    if (isset($goods['list'][$item['color_id']])) {
                        //存在颜色
                        $sizeData = $goods['list'][$item['color_id']]['sizeList'];
                        if (isset($sizeData[$item['size_id']])) {
                            // 存在尺寸
                            $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']]['inputQuantity'] += $item['goods_number'];
                        } else {
                            // 未存在尺寸
                            $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']] = [
                                'size_id' => $item['size_id'],
                                'size_name' => $sizeList[$item['size_id']]['size_name'],
                                'inputQuantity' => $item['goods_number'],
                                'stockQuantity' => $stockData[$item['goods_id'] . "-" . $item['color_id'] . "-" . $item['size_id']],
                            ];
                        }
                        $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]['inputQuantity'] += $item['goods_number'];
                    } else {
                        //不存在此颜色
                        $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']] = $colorList[$item['color_id']];
                        $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]["inputQuantity"] = $item['goods_number'];
                        $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]["stockQuantity"] = $stockData[$item['goods_id'] . "-" . $item['color_id']];
                        $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']] = $sizeList[$item['size_id']];
                        $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']]["inputQuantity"] = $item['goods_number'];
                        $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']]["stockQuantity"] = $stockData[$item['goods_id'] . "-" . $item['color_id'] . "-" . $item['size_id']];;
                    }
                    $res[$item['children_code']]['list'][$item['goods_id']]['inputQuantity'] += $item['goods_number'];
                } else {
                    //获取商品
                    $goods = $goodsList[$item['goods_id']];
                    $goods['inputQuantity'] = $item['goods_number'];
                    $goods['stockQuantity'] = $stockData[$item['goods_id']];
                    $res[$item['children_code']]['list'][$item['goods_id']] = $goods;
                    $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']] = $colorList[$item['color_id']];
                    $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]["inputQuantity"] = $item['goods_number'];
                    $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]["stockQuantity"] = $stockData[$item['goods_id'] . "-" . $item['color_id']];
                    $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']] = $sizeList[$item['size_id']];
                    $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']]["inputQuantity"] = $item['goods_number'];
                    $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']]["stockQuantity"] = $stockData[$item['goods_id'] . "-" . $item['color_id'] . "-" . $item['size_id']];
                }
            }else{//不存在子单
                $res[$item['children_code']] = $childList[$item['children_code']];
                //获取商品
                $goods = $goodsList[$item['goods_id']];
                $goods['inputQuantity'] = $item['goods_number'];
                $goods['stockQuantity'] = $stockData[$item['goods_id']];
                $res[$item['children_code']]['list'][$item['goods_id']] = $goods;
                $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']] = $colorList[$item['color_id']];
                $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]["inputQuantity"] = $item['goods_number'];
                $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]["stockQuantity"] = $stockData[$item['goods_id'] . "-" . $item['color_id']];
                $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']] = $sizeList[$item['size_id']];
                $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']]["inputQuantity"] = $item['goods_number'];
                $res[$item['children_code']]['list'][$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']]["stockQuantity"] = $stockData[$item['goods_id'] . "-" . $item['color_id'] . "-" . $item['size_id']];
            }
        }
        //去除key
        $res = array_values($res);
//        //遍历商品
        $res = array_map(function ($a) use ($goodsList) {
            $a['list'] = array_map(function ($b) {
                $b['list'] = array_map(function($c){
                    $c['sizeList'] = array_values($c['sizeList']);
                    return $c;
                },$b['list']);
                $b['list'] = array_values($b['list']);
                return $b;
            }, $a['list']);
            $a['list'] = array_values($a['list']);
            return $a;
        }, $res);

        $orderData['children'] = $res;
        return $orderData;
    }

    /**
     * 根据盘点单id获取详情
     * zll
     * Date: 2021/5/26
     * Time: 11:53
     * @param $orders_id
     * @return bool
     */
    public function getByOrdersId($orders_id)
    {
        $order = $this
            ->with(["organization"])
            ->where("orders_id", $orders_id)
            ->find();
        if (!$order) {
            $this->error = "此盘点单不存在";
            return false;
        }
        //查询子单据列表
        $iocModel = new InventoryOrdersChildren();
        $childrenList = $iocModel
            ->with(["detailslist", "detailsclist"])
            ->where("orders_id", "=", $orders_id)
            ->select();
        foreach ($childrenList as $key => $children) {
            //查出子单据商品列表
            $goodsModel = new GoodsModel();
            $iodModel = new InventoryOrdersDetailsModel();
            $iodcModel = new InventoryOrdersDetailsCModel();
            $colorModel = new Color();
            $sizeModel = new Size();

            $detailModel = $iodModel;

            if ($order['orders_status'] == 9) {//正式
                $detailModel = $iodcModel;
            }
            $childrenList[$key]['inputQuantity'] = $detailModel->where("orders_id", "=", $orders_id)
                ->where("children_code", "=", $children['children_code'])
                ->sum("goods_number");
            //商品列表id
            $goods_ids = $detailModel
                ->where("orders_id", "=", $orders_id)
                ->where("children_code", "=", $children['children_code'])
                ->group("goods_id")
                ->column("goods_id");
            //获取子单据商品列表 关联商品图片
            $goodsList = $goodsModel->with(['images'])->where("goods_id", "in", $goods_ids)->select();

            foreach ($goodsList as $gidk => $goods) {
                $colorids = $detailModel
                    ->where("orders_id", "=", $orders_id)
                    ->where("children_code", "=", $children['children_code'])
                    ->where("goods_id", "=", $goods['goods_id'])
                    ->group("color_id")
                    ->column("color_id");
                $goodsList[$gidk]['inputQuantity'] = $detailModel
                    ->where("orders_id", "=", $orders_id)
                    ->where("children_code", "=", $children['children_code'])
                    ->where("goods_id", "=", $goods['goods_id'])
                    ->sum("goods_number");
                $goodsList[$gidk]['stockQuantity'] = GoodsStockModel::where("goods_id", "=", $goods['goods_id'])
                    ->where("warehouse_id", "=", $order['warehouse_id'])
                    ->sum("stock_number");
                $goodsList[$gidk]['list'] = $colorModel->where("color_id", "in", $colorids)->select();

                foreach ($goodsList[$gidk]['list'] as $kk => $color) {
                    $sizeids = $detailModel
                        ->where("orders_id", "=", $orders_id)
                        ->where("children_code", "=", $children['children_code'])
                        ->where("goods_id", "=", $goods['goods_id'])
                        ->where("color_id", "=", $color['color_id'])
                        ->column("size_id");
                    $goodsList[$gidk]['list'][$kk]['inputQuantity'] = $detailModel
                        ->where("orders_id", "=", $orders_id)
                        ->where("children_code", "=", $children['children_code'])
                        ->where("goods_id", "=", $goods['goods_id'])
                        ->where("color_id", "=", $color['color_id'])
                        ->sum("goods_number");
                    $goodsList[$gidk]['list'][$kk]['stockQuantity'] = GoodsStockModel::where("goods_id", "=", $goods['goods_id'])
                        ->where("color_id", "=", $color['color_id'])
                        ->where("warehouse_id", "=", $order['warehouse_id'])
                        ->sum("stock_number");
                    $goodsList[$gidk]['list'][$kk]['sizeList'] = $sizeModel->where("size_id", "in", $sizeids)->select();
                    foreach ($goodsList[$gidk]['list'][$kk]['sizeList'] as $skey => $sizedata) {
                        $goodsList[$gidk]['list'][$kk]['sizeList'][$skey]['inputQuantity'] = $detailModel
                            ->where("orders_id", "=", $orders_id)
                            ->where("children_code", "=", $children['children_code'])
                            ->where("goods_id", "=", $goods['goods_id'])
                            ->where("color_id", "=", $color['color_id'])
                            ->where("size_id", "=", $sizedata['size_id'])
                            ->value("goods_number");

                        $goodsList[$gidk]['list'][$kk]['sizeList'][$skey]['stockQuantity'] = GoodsStockModel::where("goods_id", "=", $goods['goods_id'])
                            ->where("color_id", "=", $color['color_id'])
                            ->where("size_id", "=", $sizedata['size_id'])
                            ->where("warehouse_id", "=", $order['warehouse_id'])
                            ->value("stock_number");
                    }
                }
            }


            $childrenList[$key]['list'] = $goodsList;

        }
        $order['children'] = $childrenList;
        return $order;
    }


    //删除草稿盘点单
    public function deleteRow($orders_id)
    {
        $order = $this->where("orders_id", "=", $orders_id)->find();
        if (!$order) {
            $this->error = "该盘点单不存在";
            return false;
        }
        if ($order['orders_status'] != 1) {
            $this->error = "该盘点单不是草稿状态";
            return false;
        }

        $this->startTrans();
        try {
            if (!$order->delete()) {
                throw new Exception("删除盘点单失败!");
            }
            $iodModel = new InventoryOrdersDetails();
            $iodModel->where("orders_id", "=", $orders_id)->delete();

            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }

    }

    //根据单id查询盘点单
    public function getById($orders_id)
    {
        return $this
            ->with(['childrens' => ['detailslist', 'detailsclist'], 'organization'])
            ->where("orders_id", "=", $orders_id)
            ->find();
    }

    /**
     * zll
     * 查询列表
     * Date: 2021/5/19
     * Time: 19:00
     * @param $data 前端传回的条件
     * @param $where 后端定义的条件
     * @param int $rows
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function queryList($data, $where, $rows = 10)
    {
        $mod = $this;
        //单据号
        if (isset($data['orders_code']) && !empty($data['orders_code'])) {
            $mod = $mod->where("orders_code", "like", "%" . $data['orders_code'] . "%");
        }
        //仓库
        if (isset($data['warehouse_id']) && $data['warehouse_id'] > 0) {
            $mod = $mod->where("warehouse_id", "=", $data['warehouse_id']);
        }
        //时间段
        if (isset($data['begin_time']) && isset($data['end_time'])) {
            $mod = $mod->where("create_time", "between", [$data['begin_time'], $data['end_time']]);
        }
        //单子状态
        if (isset($data['orders_status']) && $data['orders_status'] >= 0) {
            $mod = $mod->where("orders_status", "=", $data['orders_status']);
        };

        $list = $mod
            ->with(['childrens' => ['detailslist', 'detailsclist'], 'organization', "user"])
            ->where($where)
            ->order("orders_id", "desc")
            ->paginate($rows);

        return $list;
    }

    //提交盘点单
    public function submit($data)
    {
        //开启事务
        $this->startTrans();
        //存储商品id数组
        $goods_ids = [];
        //商品重复判断
        if ($this->checkRepeatGoods($data)) {
            $this->error = "子单中不能有重复商品";
            return false;
        }
        try {
            //盘点单编号
            $order_code = BuildCode::dateCode("SI");
            //添加盘点单基本信息
            $order = [
                "orders_code" => $order_code,
                "warehouse_id" => $data['warehouse_id'],//仓库id
                "goods_number" => $data['goods_number'],//盘点总数
                "goods_plnumber" => 0,//$data['goods_plnumber'],//盈亏数量
                "user_id" => $data['user_id'],
                "orders_status" => $data['orders_status'],//状态 0未保存1草稿9完成(正式单)
                "orders_remark" => $data['orders_remark'],
                "orders_date" => $data['orders_date'],
                "com_id" => $data['com_id'],//企业id
                "shop_id" => 0,//$data['shop_id'],//门店id
            ];

            //保存盘点单基础数据
            $selfOrder = static::create($order);
            if (!$selfOrder) {
                throw new Exception("添加盘点单基础信息出错!");
            }

            //添加子单据信息

            $childrenList = json_decode($data['children'], true);
            foreach ($childrenList as $key => $item) {
                //子单据编号
                $childrenCode = BuildCode::dateCode("SIC");
                $childrenItem = [
                    "orders_id" => $selfOrder->orders_id,
                    "user_id" => $data['user_id'],
                    "orders_code" => $order_code,
                    "serial_number" => $data['user_id'] . ($key + 1),
                    "children_code" => $data['user_id'] . ($key + 1),
                    "children_name" => $item['children_name'],
                    "goods_status" => 1,//$item['goods_status'],
                    "curr_use" => 1,//$item['curr_use'],
                    "com_id" => $data['com_id'],//企业id
                ];

                //保存盘点单 子单
                $childrenResult = (new InventoryOrdersChildren())::create($childrenItem);
                if (!$childrenResult) {
                    throw new Exception("添加盘点单(子单)信息出错!");
                }

                /**
                 * 拼接盘点详情数据 根据选择是草稿还是正式,区别仅为正式单多出:
                 * goods_lnumber盈亏数量
                 * goods_lmoney盈亏金额
                 * 以及保存的表不同
                 */

                //拼接盘点单 详情
                $odlist = [];
                $stock_data = [];//库存更新数组
                $stock_diary_data = [];//库存更新记录
                foreach ($item['list'] as $oditem) {
                    $goods_ids[] = $oditem['goods_id'];
                    //盘点前库存
                    $before_stock = (new GoodsStockModel())->getStockValue(["warehouse_id" => $data['warehouse_id'], "goods_id" => $oditem['goods_id'], "color_id" => $oditem['color_id'], "size_id" => $oditem['size_id']], 'stock_number');
                    //商品信息
                    $goodsInfo = (new GoodsModel())->where("goods_id", "=", $oditem['goods_id'])->find();
                    $od = [];
                    $od = [
                        "warehouse_id" => $data['warehouse_id'],//仓库id
                        "orders_id" => $selfOrder->orders_id,
                        "orders_code" => $order_code,
                        "children_code" => $data['user_id'] . ($key + 1),
                        "user_id" => $data['user_id'],//用户id
                        "goods_id" => $oditem['goods_id'],
                        "goods_code" => $oditem['goods_code'],
                        "color_id" => $oditem['color_id'],
                        "size_id" => $oditem['size_id'],
                        "goods_number" => $oditem['goods_number'],//盘点数量
                        "goods_anumber" => $before_stock,//盘点前数量
                        "goods_status" => $oditem['goods_status'],//商品状态
                        "com_id" => $data['com_id'],//企业id
                        "shop_id" => 1,//$oditem['shop_id'],//企业id
                    ];
                    $stock_data[] = [
                        "warehouse_id" => $data['warehouse_id'],
                        "goods_id" => $oditem['goods_id'],
                        "color_id" => $oditem['color_id'],
                        "size_id" => $oditem['size_id'],
                        "stock_number" => $oditem['goods_number'],
                        "last_orders_code" => $order_code,
                        "com_id" => $data['com_id'],
                        "shop_id" => 1,
                    ];

                    $stock_diary_data[] = [
                        "warehouse_id" => $data['warehouse_id'],
                        "orders_code" => $order_code,
                        "goods_id" => $oditem['goods_id'],
                        "color_id" => $oditem['color_id'],
                        "size_id" => $oditem['size_id'],
                        "goods_number" => $oditem['goods_number'],
                        "stock_number" => $before_stock,
                        "orders_type" => '盘点单-提交正式',
                        "goods_code" => $oditem['goods_code'],
                        "com_id" => $data['com_id'],//$oditem['com_id'],//企业id
                        "shop_id" => 0,//$oditem['shop_id'],//门店id
                    ];

                    //如果是正式单
                    if ($data['orders_status'] == 9) {
                        $od['goods_lnumber'] = $oditem['goods_number'] - $before_stock;
                        $od['goods_lmoney'] = bcmul($od['goods_lnumber'],$goodsInfo['goods_pprice'],2);//$ob['goods_lnumber'] * $goodsInfo['goods_pprice'];
                    }
                    $odlist[] = $od;
                }

                //获取此单据所有商品当前库存总和
                $stock_sum_num_where = [
                    ["warehouse_id", "=", $data['warehouse_id']],
                    ["goods_id", 'in', $goods_ids]
                ];
                $sumStockNumber = GoodsStockModel::getInventoryNumber($stock_sum_num_where);

                $this
                    ->where("orders_id", "=", $selfOrder->orders_id)
                    ->update(["goods_plnumber" => ($data['goods_number'] - $sumStockNumber)]);


                //进行正式单或草稿的判断
                if ($data['orders_status'] == 9) {
                    //批量添加正式单 详情
                    if (!(new InventoryOrdersDetailsCModel())->saveAll($odlist)) {
                        throw new Exception("正式单详情添加出错!");
                    }

                    //更新库存
                    (new GoodsStockModel())->updateForInventory($stock_data);

                    //添加 库存更新记录
                    if (!StockDiary::addStockDiary($stock_diary_data)) {
                        throw new Exception("添加库存更新记录失败");
                    }

                } elseif ($data['orders_status'] == 1) {
                    //批量添加草稿详情
                    if (!(new InventoryOrdersDetailsModel())->saveAll($odlist)) {
                        throw new Exception("草稿详情添加出错!");
                    }

                }

            }


            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    //修改盘点单
    public function editOrder($data)
    {
        //获取盘点单基础信息
        $order = $this->where("orders_id", "=", $data['orders_id'])->find();
        if (!$order) {
            $this->error = "此单不存在";
            return false;
        }
        if ($order['orders_status'] == 9) {
            $this->error = "此单已完成";
            return false;
        }
        if ($this->checkRepeatGoods($data)) {
            $this->error = "子单中不能有重复商品";
            return false;
        }
        //存储商品id数组
        $goods_ids = [];
        $this->startTrans();
        try {
            //删除子单据
            $iocModel = new InventoryOrdersChildren();
            if (!$iocModel->where("orders_id", "=", $data['orders_id'])->delete()) {
                throw new Exception("删除子单据失败");
            }
            //删除详情数据
            $iodModel = new InventoryOrdersDetailsModel();
            if (!$iodModel->where("orders_id", "=", $data['orders_id'])->delete()) {
                throw new Exception("删除盘点单详情失败");
            }
            //更新盘点单基础数据
            $order->warehouse_id = $data["warehouse_id"];
            $order->goods_number = $data["goods_number"];
            $order->goods_plnumber = $data["goods_plnumber"];
            $order->user_id = $data["user_id"];
            $order->orders_status = $data["orders_status"];
            $order->orders_remark = $data["orders_remark"];
            $order->orders_date = $data["orders_date"];
            $order->lock_version = $data["lock_version"];
            $order->com_id = $data["com_id"];
            $order->shop_id = 1;//$data["shop_id"];
            if (!$order->save()) {
                throw new Exception("保存盘点单信息失败");
            }

            //添加子单据信息

            $childrenList = json_decode($data['children'], true);
            foreach ($childrenList as $key => $item) {
                //子单据编号
                $childrenCode = BuildCode::dateCode("SIC");
                $childrenItem = [
                    "orders_id" => $order['orders_id'],
                    "user_id" => $data['user_id'],
                    "orders_code" => $order['orders_code'],
                    "serial_number" => $data['user_id'] . ($key + 1),
                    "children_code" => $data['user_id'] . ($key + 1),
                    "children_name" => $item['children_name'],
                    "goods_status" => 1,//$item['goods_status'],
                    "curr_use" => 1,//$item['curr_use'],
                    "com_id" => $data['com_id'],//企业id
                ];

                //保存盘点单 子单
                $childrenResult = (new InventoryOrdersChildren())::create($childrenItem);
                if (!$childrenResult) {
                    throw new Exception("添加盘点单(子单)信息出错!");
                }

                /**
                 * 拼接盘点详情数据 根据选择是草稿还是正式,区别仅为正式单多出:
                 * goods_lnumber盈亏数量
                 * goods_lmoney盈亏金额
                 * 以及保存的表不同
                 */

                //拼接盘点单 详情
                $odlist = [];
                $stock_data = [];//库存更新数组
                $stock_diary_data = [];//库存更新记录
                foreach ($item['list'] as $oditem) {
                    $goods_ids[] = $oditem['goods_id'];
                    //盘点前库存
                    $before_stock = (new GoodsStockModel())->getStockValue(["warehouse_id" => $data['warehouse_id'], "goods_id" => $oditem['goods_id'], "color_id" => $oditem['color_id'], "size_id" => $oditem['size_id']], 'stock_number');
                    //商品信息
                    $goodsInfo = (new GoodsModel())->where("goods_id", "=", $oditem['goods_id'])->find();
                    $od = [];
                    $od = [
                        "warehouse_id" => $data['warehouse_id'],//仓库id
                        "orders_id" => $order['orders_id'],
                        "orders_code" => $order['orders_code'],
                        "children_code" => $data['user_id'] . ($key + 1),
                        "user_id" => $data['user_id'],//用户id
                        "goods_id" => $oditem['goods_id'],
                        "goods_code" => $oditem['goods_code'],
                        "color_id" => $oditem['color_id'],
                        "size_id" => $oditem['size_id'],
                        "goods_number" => $oditem['goods_number'],//盘点数量
                        "goods_anumber" => $before_stock,//盘点前数量
                        "goods_status" => $oditem['goods_status'],//商品状态
                        "com_id" => $data['com_id'],//企业id
                        "shop_id" => 1,//$oditem['shop_id'],//企业id
                    ];
                    $stock_data[] = [
                        "warehouse_id" => $data['warehouse_id'],
                        "goods_id" => $oditem['goods_id'],
                        "color_id" => $oditem['color_id'],
                        "size_id" => $oditem['size_id'],
                        "stock_number" => $oditem['goods_number'],
                        "last_orders_code" => $order['orders_code'],
                        "com_id" => $data['com_id'],
                        "shop_id" => 1,
                    ];

                    $stock_diary_data[] = [
                        "warehouse_id" => $data['warehouse_id'],
                        "orders_code" => $order['orders_code'],
                        "goods_id" => $oditem['goods_id'],
                        "color_id" => $oditem['color_id'],
                        "size_id" => $oditem['size_id'],
                        "goods_number" => $oditem['goods_number'],
                        "stock_number" => $before_stock,
                        "orders_type" => '盘点单-提交正式',
                        "goods_code" => $oditem['goods_code'],
                        "com_id" => $data['com_id'],//企业id
                        "shop_id" => 1,//$oditem['shop_id'],//门店id
                    ];

                    //如果是正式单
                    if ($data['orders_status'] == 9) {
                        $od['goods_lnumber'] = $oditem['goods_number'] - $before_stock;
                        $od['goods_lmoney'] = bcmul($od['goods_lnumber'],$goodsInfo['goods_pprice'],2);//$ob['goods_lnumber'] * $goodsInfo['goods_pprice'];
                    }

                    $odlist[] = $od;
                }

                //获取此单据所有商品当前库存总和
                $stock_sum_num_where = [
                    ["warehouse_id", "=", $data['warehouse_id']],
                    ["goods_id", 'in', $goods_ids]
                ];
                $sumStockNumber = GoodsStockModel::getInventoryNumber($stock_sum_num_where);
                $this
                    ->where("orders_id", "=", $data['orders_id'])
                    ->update(["goods_plnumber" => ($data['goods_number'] - $sumStockNumber)]);

                //进行正式单或草稿的判断
                if ($data['orders_status'] == 9) {
                    //批量添加正式单 详情
                    if (!(new InventoryOrdersDetailsCModel())->saveAll($odlist)) {
                        throw new Exception("正式单详情添加出错!");
                    }

                    //更新库存
                    (new GoodsStockModel())->updateForInventory($stock_data);

                    //添加 库存更新记录
                    if (!StockDiary::addStockDiary($stock_diary_data)) {
                        throw new Exception("添加库存更新记录失败");
                    }

                } elseif ($data['orders_status'] == 1) {
                    //批量添加草稿详情
                    if (!(new InventoryOrdersDetailsModel())->saveAll($odlist)) {
                        throw new Exception("草稿详情添加出错!");
                    }

                }

            }
            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    //草稿转正式
    public function draftToOfficial($data)
    {
        $orders_id = $data['orders_id'];
        //获取盘点单基础信息
        $order_only = $this->where("orders_id", "=", $data['orders_id'])->find();

        $order = $this
            ->where("orders_id", "=", $orders_id)
            ->with(["childrens" => ['detailslist', 'detailsclist']])
            ->find();

        if (!$order) {
            $this->error = "盘点单-草稿不存在";
            return false;
        }
        if ($order['orders_status'] != 1) {
            $this->error = "盘点单非草稿类型";
            return false;
        }
        if ($this->checkRepeatGoods($data)) {
            $this->error = "子单中不能有重复商品";
            return false;
        }
        //存储商品id数组
        $goods_ids = [];
        $this->startTrans();
        try {
            //更新盘点单基础数据
            $order_only->warehouse_id = $data["warehouse_id"];
            $order_only->goods_number = $data["goods_number"];
            $order_only->goods_plnumber = $data["goods_plnumber"];
            $order_only->user_id = $data["user_id"];
            $order_only->orders_status = $data["orders_status"];
            $order_only->orders_remark = $data["orders_remark"];
            $order_only->orders_date = $data["orders_date"];
            $order_only->lock_version = $data["lock_version"];
            $order_only->com_id = $data["com_id"];
            $order_only->shop_id = 1;//$data["shop_id"];

            if (!$order_only->save()) {
                throw new Exception("保存盘点单信息失败");
            }

            //删除子单数据
            if (!(new InventoryOrdersChildren())->where("orders_id", "=", $orders_id)->delete()) {
                throw new Exception("删除原子单数据失败");
            }
            //删除草稿数据
            if (!(new InventoryOrdersDetails())->deleteByOrderId($orders_id)) {
                throw new Exception("删除草稿数据失败");
            }


            //拼接盘点单 详情
            $odlist = [];

            //拼接正式子单和单详情数据
            $detailscData = [];
            $stock_data = [];//库存更新数组
            $stock_diary_data = [];//库存更新记录
            $childrens = json_decode($data['children'], true);
            foreach ($childrens as $k => $item) {
                $childrenItem = [
                    "orders_id" => $order['orders_id'],
                    "user_id" => $data['user_id'],
                    "orders_code" => $order['orders_code'],
                    "serial_number" => $data['user_id'] . ($k + 1),
                    "children_code" => $data['user_id'] . ($k + 1),
                    "children_name" => $item['children_name'],
                    "goods_status" => 1,//$item['goods_status'],
                    "curr_use" => 1,//$item['curr_use'],
                    "com_id" => $data['com_id'],//企业id
                ];

                //保存盘点单 子单
                $childrenResult = (new InventoryOrdersChildren())::create($childrenItem);
                if (!$childrenResult) {
                    throw new Exception("盘点单(子单)信息出错!");
                }

                foreach ($item['list'] as $oditem) {
                    $goods_ids[] = $oditem['goods_id'];
                    //盘点前库存
                    $before_stock = (new GoodsStockModel())->getStockValue(["warehouse_id" => $data['warehouse_id'], "goods_id" => $oditem['goods_id'], "color_id" => $oditem['color_id'], "size_id" => $oditem['size_id']], 'stock_number');
                    //商品信息
                    $goodsInfo = (new GoodsModel())->where("goods_id", "=", $oditem['goods_id'])->find();
                    $od = [];
                    $od = [
                        "warehouse_id" => $data['warehouse_id'],//仓库id
                        "orders_id" => $order_only['orders_id'],
                        "orders_code" => $order_only['orders_code'],
                        "children_code" => $data['user_id'] . ($k + 1),
                        "user_id" => $data['user_id'],//用户id
                        "goods_id" => $oditem['goods_id'],
                        "goods_code" => $oditem['goods_code'],
                        "color_id" => $oditem['color_id'],
                        "size_id" => $oditem['size_id'],
                        "goods_number" => $oditem['goods_number'],//盘点数量
                        "goods_anumber" => $before_stock,//盘点前数量
                        "goods_status" => $oditem['goods_status'],//商品状态
                        "com_id" => $data['com_id'],//企业id
                        "shop_id" => 1,//$oditem['shop_id'],//企业id
                    ];
                    $stock_data[] = [
                        "warehouse_id" => $data['warehouse_id'],
                        "goods_id" => $oditem['goods_id'],
                        "color_id" => $oditem['color_id'],
                        "size_id" => $oditem['size_id'],
                        "stock_number" => $oditem['goods_number'],
                        "last_orders_code" => $order_only['orders_code'],
                        "com_id" => $data['com_id'],
                        "shop_id" => 1,
                    ];

                    $stock_diary_data[] = [
                        "warehouse_id" => $data['warehouse_id'],
                        "orders_code" => $order_only['orders_code'],
                        "goods_id" => $oditem['goods_id'],
                        "color_id" => $oditem['color_id'],
                        "size_id" => $oditem['size_id'],
                        "goods_number" => $oditem['goods_number'],
                        "stock_number" => $before_stock,
                        "orders_type" => '盘点单-草转正',
                        "goods_code" => $oditem['goods_code'],
                        "com_id" => $data['com_id'],//$oditem['com_id'],//企业id
                        "shop_id" => 0,//$oditem['shop_id'],//门店id
                    ];

                    //如果是正式单
                    if ($data['orders_status'] == 9) {
                        $od['goods_lnumber'] = $oditem['goods_number'] - $before_stock;
                        $od['goods_lmoney'] = bcmul($od['goods_lnumber'],$goodsInfo['goods_pprice'],2);//$ob['goods_lnumber'] * $goodsInfo['goods_pprice'];
                    }

                    $odlist[] = $od;
                }
            }

            //获取此单据所有商品当前库存总和
            $stock_sum_num_where = [
                ["warehouse_id", "=", $data['warehouse_id']],
                ["goods_id", 'in', $goods_ids]
            ];
            $sumStockNumber = GoodsStockModel::getInventoryNumber($stock_sum_num_where);
            $this
                ->where("orders_id", "=", $data['orders_id'])
                ->update(["goods_plnumber" => ($data['goods_number'] - $sumStockNumber)]);

            //批量添加正式单 详情
            if (!(new InventoryOrdersDetailsCModel())->saveAll($odlist)) {
                throw new Exception("正式单详情添加出错!");
            }

            //更新库存
            (new GoodsStockModel())->updateForInventory($stock_data);

            //添加 库存更新记录
            if (!StockDiary::addStockDiary($stock_diary_data)) {
                throw new Exception("添加库存更新记录失败");
            }

            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 检测子单是否有重复商品
     * zll
     * Date: 2021/6/2
     * Time: 18:15
     * @param $data
     * @return bool
     */
    public function checkRepeatGoods($data)
    {
        $goods_ids = [];
        $childrenList = json_decode($data['children'], true);
        foreach ($childrenList as $key => $item) {
            foreach ($item['list'] as $oditem) {
                $goods_ids[] = $oditem['goods_id'] . $oditem['color_id'] . $oditem['size_id'];
            }
        }
        if (count($goods_ids) != count(array_unique($goods_ids))) {
            return true;
        }
        return false;
    }
}