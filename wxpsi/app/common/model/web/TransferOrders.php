<?php


namespace app\common\model\web;


use app\common\model\BaseModel;
use app\common\utils\BuildCode;
use think\Exception;
use think\facade\Db;
use app\web\model\Goods as GoodsModel;
use app\web\model\GoodsStock as GoodsStockModel;
use app\common\model\web\TransferOrdersDetails as TransferOrdersDetailsModel;

class TransferOrders extends BaseModel
{
    protected $table = 'hr_transfer_orders';
    protected $pk = 'orders_id';
    //关联入仓库
    public function inorg(){
        return $this->hasOne(Organization::class,"org_id","in_warehouse_id");
    }
    //关联出仓库
    public function outorg(){
        return $this->hasOne(Organization::class,"org_id","out_warehouse_id");
    }
    //关联制单员
    public function user(){
        return $this->hasOne(User::class,"user_id","user_id");
    }

    //调拨单 列表查询
    public function queryList($data,$where,$rows=10){
        $mod = $this;
        //单据号
        if(isset($data['orders_code']) && !empty($data['orders_code'])){
            $mod = $mod->where("orders_code","like","%".$data['orders_code']."%");
        }
        //出仓库
        if(isset($data['out_warehouse_id']) && $data['out_warehouse_id']>0){
            $mod = $mod->where("out_warehouse_id","=",$data['out_warehouse_id']);
        }
        //入仓库
        if(isset($data['in_warehouse_id']) && $data['in_warehouse_id']>0){
            $mod = $mod->where("in_warehouse_id","=",$data['in_warehouse_id']);
        }
        //时间段
        if(isset($data['begin_time']) && isset($data['end_time'])) {
            $mod = $mod->where("update_time","between",[$data['begin_time'],$data['end_time']]);
        }
        //单子状态
        if(isset($data['orders_status']) && $data['orders_status']>=0 ) {
            $mod = $mod->where("orders_status","=",$data['orders_status']);
        }

        return $mod
            ->with(['details','inorg','outorg','user'])
            ->where($where)
            ->order("orders_id","desc")
            ->paginate($rows);
    }

    //优化的 根据单据id获取单据详情
    public function getByOrdersId_($orders_id){
        $detailModel = new TransferOrdersDetailsModel();    //调拨单详情模型
        $goodModel = new GoodsModel();                      //商品模型
        $gdModle = new GoodsDetails();                      //商品详情模型
        $colorModel = new Color();                          //颜色模型
        $sizeModel = new Size();                            //尺码模型
        $goodsStockModel = new GoodsStockModel();           //库存模型

        //获取调拨单基础数据
        $orderData = $this
            ->where("orders_id","=",$orders_id)
            ->with(['inorg','outorg'])
            ->find();

        //获取调拨单详情列表
        $detailList = $detailModel->where("orders_id","=",$orders_id)->select()->toArray();

        //获取相关商品列表
        $goods_ids = array_values(array_unique(array_column($detailList,"goods_id")));
        $goodsListData = $goodModel->with(['images'])->where("goods_id","in",$goods_ids)->select()->toArray();
        $goodsList = [];
        foreach ($goodsListData as $v){
            $goodsList[$v['goods_id']] = $v;
        }
        //获取所有相关颜色
        $color_ids = array_values(array_unique(array_column($detailList,"color_id")));
        $colorList = $colorModel->where("color_id","in",$color_ids)->column("*","color_id");

        //获取所有相关尺码
        $size_ids = array_values(array_unique(array_column($detailList,"size_id")));
        $sizeList = $sizeModel->where("size_id","in",$size_ids)->column("*","size_id");

        //获取库存列表数据,并且加工一下
        $goodsStockList = $goodsStockModel
            ->where("warehouse_id","=",$orderData['out_warehouse_id'])
            ->where("goods_id","in",$goods_ids)
            ->select();
        $stockData = getStockGoodsColorSize($goodsStockList);

        $res = [];
        foreach ($detailList as $item){

            //判断是否存在商品
            if(isset($res[$item['goods_id']])){
                $goods = $res[$item['goods_id']];
                if(isset($goods['list'][$item['color_id']])){
                    //存在颜色
                    $sizeData = $goods['list'][$item['color_id']]['sizeList'];
                    if (isset($sizeData[$item['size_id']])) {
                        // 存在尺寸
                        $res[$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']]['inputQuantity'] += $item['goods_number'];
                    } else {
                        // 未存在尺寸
                        $res[$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']] = [
                            'size_id' => $item['size_id'],
                            'size_name' => $sizeList[$item['size_id']]['size_name'],
                            'inputQuantity' => $item['goods_number'],
                            'stockQuantity' => $stockData[$item['goods_id']."-".$item['color_id']."-".$item['size_id']],
                        ];
                    }
                    $res[$item['goods_id']]['list'][$item['color_id']]['inputQuantity'] += $item['goods_number'];
                }else{
                    //不存在此颜色
                    $res[$item['goods_id']]['list'][$item['color_id']] = $colorList[$item['color_id']];
                    $res[$item['goods_id']]['list'][$item['color_id']]["inputQuantity"] = $item['goods_number'];
                    $res[$item['goods_id']]['list'][$item['color_id']]["stockQuantity"] = $stockData[$item['goods_id']."-".$item['color_id']];
                    $res[$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']] = $sizeList[$item['size_id']];
                    $res[$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']]["inputQuantity"] = $item['goods_number'];
                    $res[$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']]["stockQuantity"] = $stockData[$item['goods_id']."-".$item['color_id']."-".$item['size_id']];;
                }
                $res[$item['goods_id']]['inputQuantity'] += $item['goods_number'];
            }else{
                //获取商品
                $goods = $goodsList[$item['goods_id']];
                $goods['inputQuantity'] = $item['goods_number'];
                $goods['stockQuantity'] = $stockData[$item['goods_id']];
                $res[$item['goods_id']] = $goods;
                $res[$item['goods_id']]['list'][$item['color_id']] = $colorList[$item['color_id']];
                $res[$item['goods_id']]['list'][$item['color_id']]["inputQuantity"] = $item['goods_number'];
                $res[$item['goods_id']]['list'][$item['color_id']]["stockQuantity"] = $stockData[$item['goods_id']."-".$item['color_id']];
                $res[$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']] = $sizeList[$item['size_id']];
                $res[$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']]["inputQuantity"] = $item['goods_number'];
                $res[$item['goods_id']]['list'][$item['color_id']]['sizeList'][$item['size_id']]["stockQuantity"] = $stockData[$item['goods_id']."-".$item['color_id']."-".$item['size_id']];
            }
        }
        //去除key
        $res = array_values($res);
//        //遍历商品
        $res = array_map(function($a)use($goodsList){
             $a['list'] = array_map(function($b){
                 $b['sizeList'] = array_values($b['sizeList']);
                 return $b;
            },$a['list']);
             $a['list'] = array_values($a['list']);
            return $a;
        },$res);

        $orderData['details'] = $res;
        return $orderData;
    }
    //根据id获取详情
    public function getByOrdersId($orders_id){
        $detailModel = new TransferOrdersDetailsModel();
        $goodModel = new GoodsModel();
        $gdModle = new GoodsDetails();
        $colorModel = new Color();
        $sizeModel = new Size();

        $data = $this
            ->where("orders_id","=",$orders_id)
            ->with(['inorg','outorg'])
            ->find();


        //查出调拨单涉及的商品列表 关联商品图片
        $ids = $detailModel->where("orders_id","=",$orders_id)->group("goods_id")->column("goods_id");
        $details = $goodModel->with(['images'])->where("goods_id","in",$ids)->select();

        foreach ($details as $k=>$goods){
            $color_ids = $gdModle->where("goods_id","=",$goods['goods_id'])->group("color_id")->column("color_id");
            $color_list = $colorModel->where("color_id","in",$color_ids)->select();
            $details[$k]['list'] = $color_list;
            $details[$k]['inputQuantity'] = $detailModel
                ->where("orders_id","=",$orders_id)
                ->where("goods_id","=",$goods['goods_id'])
                ->sum("goods_number");
            $details[$k]['stockQuantity'] = GoodsStockModel::where("goods_id","=",$goods['goods_id'])
                ->where("warehouse_id","=",$data['out_warehouse_id'])
                ->sum("stock_number");
            foreach ($details[$k]['list'] as $ck=>$color){
                $details[$k]['list'][$ck]['inputQuantity'] = $detailModel
                    ->where("orders_id","=",$orders_id)
                    ->where("goods_id","=",$goods['goods_id'])
                    ->where("color_id","=",$color['color_id'])
                    ->sum("goods_number");

                $details[$k]['list'][$ck]['stockQuantity'] = GoodsStockModel::where("goods_id","=",$goods['goods_id'])
                    ->where("color_id","=",$color['color_id'])
                    ->where("warehouse_id","=",$data['out_warehouse_id'])
                    ->sum("stock_number");
                $size_ids = $gdModle
                    ->where("goods_id","=",$goods['goods_id'])
                    ->where("color_id","=",$color['color_id'])
                    ->column("size_id")
                ;
                $size_list = $sizeModel->where("size_id","in",$size_ids)->select();
                foreach ($size_list as $sk=>$size){
                    $size_list[$sk]['inputQuantity'] = $detailModel
                        ->where("orders_id","=",$orders_id)
                        ->where("goods_id","=",$goods['goods_id'])
                        ->where("color_id","=",$color['color_id'])
                        ->where("size_id","=",$size['size_id'])
                        ->sum("goods_number");
                    $size_list[$sk]['stockQuantity'] = GoodsStockModel::where("goods_id","=",$goods['goods_id'])
                        ->where("color_id","=",$color['color_id'])
                        ->where("size_id","=",$size['size_id'])
                        ->where("warehouse_id","=",$data['out_warehouse_id'])
                        ->value("stock_number");
                }
                $details[$k]['list'][$ck]['sizeList'] = $size_list;
            }
        }
        $data['details'] = $details;
        return $data;
    }

    //删除草稿调拨单
    public function deleteRow($orders_id){
        $order = $this->where("orders_id","=",$orders_id)->find();
        if(!$order){
            $this->error = "该调拨单不存在";
            return false;
        }
        if($order['orders_status']!=0){
            $this->error = "该调拨单不是草稿状态";
            return false;
        }

        $this->startTrans();
        try{
            if(!$order->delete()){
                throw new Exception("删除调拨单失败!");
            }
            $iodModel = new TransferOrdersDetailsModel();
            $iodModel->where("orders_id","=",$orders_id)->delete();

            $this->commit();
            return true;
        }catch(Exception $e){
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }

    }

    //编辑草稿单
    public function editOrder($data){
        //库存模型
        $gsModel = new GoodsStockModel();
        //调拨单详情模型
        $tsfDetailModel = new TransferOrdersDetails();

        //开启事务
        $this->startTrans();

        try{
            $order = $this->where("orders_id","=",$data['orders_id'])->find();
            if(!$order){
                throw new Exception("调拨单不存在");
            }
            if($order['orders_status']!=0){
                throw new Exception("调拨单非草稿状态");
            }
            $order->out_warehouse_id = $data['out_warehouse_id'];
            $order->in_warehouse_id = $data['in_warehouse_id'];
            $order->goods_number = $data['goods_number'];
            $order->orders_date = $data['orders_date'];
            $order->user_id = $data['user_id'];
            $order->orders_remark = $data['orders_remark'];
            $order->com_id = $data['com_id'];
            $order->shop_id = isEmpty($data['shop_id']) ? 0 : $data['shop_id'];

            if(!$order->save()){
                throw new Exception("保存调拨单信息失败");
            }
            //删除老的详情数据
            if(!$tsfDetailModel->where("orders_id","=",$data['orders_id'])->delete()){
                throw new Exception("清空调拨单详情失败");
            }

            //调拨详情数据
            $details = json_decode($data['details'],true);
            //拼接详情数据
            $detailsData = [];
            foreach ($details as $key=>$item){
                //如果商品数量为0,跳过
                if(!$item['goods_number'] && $data['orders_status']==9){
                    continue;
                }
                //之前库存-入库
                $before_stock_in = $gsModel->getStockValue(["warehouse_id"=>$data['in_warehouse_id'],"goods_id"=>$item['goods_id'],"color_id"=>$item['color_id'],"size_id"=>$item['size_id']],'stock_number');
                //之前库存-出库
                $before_stock_out = $gsModel->getStockValue(["warehouse_id"=>$data['out_warehouse_id'],"goods_id"=>$item['goods_id'],"color_id"=>$item['color_id'],"size_id"=>$item['size_id']],'stock_number');

                //调拨单详情
                $detailsData[] = [
                    "orders_id"=>$data['orders_id'],
                    "orders_code"=>$order['orders_code'],
                    "goods_code"=>$item['goods_code'],
                    "goods_id"=>$item['goods_id'],
                    "color_id"=>$item['color_id'],
                    "size_id"=>$item['size_id'],
                    "goods_number"=>$item['goods_number'],
                    "out_warehouse_number"=>$before_stock_out ? $before_stock_out : 0,//调出仓库 当前数量
                    "in_warehouse_number"=>$before_stock_in ? $before_stock_in : 0,//调入仓库 当前数量
                    "goods_status"=>$item['goods_status'],//商品状态
                    "com_id"=>$data['com_id'],
                ];
            }
            //插入详情数据
            if(!$tsfDetailModel->saveAll($detailsData)){
                throw new Exception("保存调拨单详情失败");
            }

            $this->commit();
            return true;
        }catch(Exception $e){
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    //提交调拨单
    public function submit($data){
        //库存模型
        $gsModel = new GoodsStockModel();
        //调拨单详情模型
        $tsfDetailModel = new TransferOrdersDetails();

        //开启事务
        $this->startTrans();
        try{
            //调拨单编号
            $order_code = BuildCode::dateCode("TF");
            //添加盘点单基本信息
            $order = [
                "orders_code"=>$order_code,
                "out_warehouse_id"=>$data['out_warehouse_id'],//出仓库id
                "in_warehouse_id"=>$data['in_warehouse_id'],//入仓库id
                "goods_number"=>$data['goods_number'],//商品总数
                "orders_date"=>$data['orders_date'],//单据日期
                "user_id"=>$data['user_id'],
                "orders_status"=>$data['orders_status'],//状态 0草稿9完成(正式单)
                "orders_remark"=>$data['orders_remark'],
                "com_id"=>$data['com_id'],//企业id
                "shop_id"=>1,//$data['shop_id'],//门店id
            ];

            //保存调拨单基础数据
            $selfOrder = static::create($order);
            if(!$selfOrder){
                throw new Exception("添加调拨单基础信息出错!");
            }



            //调拨详情数据
            $details = json_decode($data['details'],true);
            //拼接详情数据
            $detailsData = [];
            foreach ($details as $key=>$item){
                //如果商品数量为0,跳过
                if(!$item['goods_number'] && $data['orders_status']==9){
                    continue;
                }
                //之前库存-入库
                $before_stock_in = $gsModel->getStockValue(["warehouse_id"=>$data['in_warehouse_id'],"goods_id"=>$item['goods_id'],"color_id"=>$item['color_id'],"size_id"=>$item['size_id']],'stock_number');
                //之前库存-出库
                $before_stock_out = $gsModel->getStockValue(["warehouse_id"=>$data['out_warehouse_id'],"goods_id"=>$item['goods_id'],"color_id"=>$item['color_id'],"size_id"=>$item['size_id']],'stock_number');

                //调拨单详情
                $detailsData[] = [
                    "orders_id"=>$selfOrder->orders_id,
                    "orders_code"=>$order_code,
                    "goods_code"=>$item['goods_code'],
                    "goods_id"=>$item['goods_id'],
                    "color_id"=>$item['color_id'],
                    "size_id"=>$item['size_id'],
                    "goods_number"=>$item['goods_number'],
                    "out_warehouse_number"=>$before_stock_out ? $before_stock_out : 0,//调出仓库 当前数量
                    "in_warehouse_number"=>$before_stock_in ? $before_stock_in : 0,//调入仓库 当前数量
                    "goods_status"=>$item['goods_status'],//商品状态
                    "com_id"=>1,//$item['com_id'],
                ];

            }
//            dump("调拨单详情");
//            dump($detailsData);
            //插入详情数据
            if(!$tsfDetailModel->saveAll($detailsData)){
                throw new Exception("保存调拨单详情失败");
            }
            if($data['orders_status']==9){
                //操作库存
                if(!$this->outInExe($order,$detailsData)){
                    throw new Exception("调拨单库存失败-".$this->error);
                }
            }


            $this->commit();
            return true;
        }catch (Exception $e){
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    //草稿转正式单
    public function draftToOfficial($data){
        $orders_id = $data['orders_id'];
        $toModel = new TransferOrders();
        $todModel = new TransferOrdersDetails();
        //库存模型
        $gsModel = new GoodsStockModel();

        $order = $toModel->where("orders_id","=",$orders_id)->find();
        $details = $todModel->where("orders_id","=",$orders_id)->select()->toArray();

        if(!$order){
            $this->error = "该调拨单不存在";
            return false;
        }

        if($order['orders_status']!=0){
            $this->error = "该调拨单非草稿状态";
            return false;
        }

        $this->startTrans();
        try{
            //更新order对象
            $order->out_warehouse_id = $data['out_warehouse_id'];
            $order->in_warehouse_id = $data['in_warehouse_id'];
            $order->goods_number = $data['goods_number'];
            $order->orders_date = $data['orders_date'];
            $order->user_id = $data['user_id'];
            $order->orders_status = 9;
            $order->orders_remark = $data['orders_remark'];
            $order->lock_version = Db::raw("lock_version+1");
            $order->com_id = $data['com_id'];
            $order->shop_id = $data['shop_id'];

            //更新基础信息
            if(!$order->save()){
                throw new Exception("编辑调拨单信息失败");
            }
            //调拨详情数据
            $details = json_decode($data['details'],true);
            //拼接详情数据
            $detailsData = [];
            foreach ($details as $key=>$item){
                //如果商品数量为0,跳过
                if(!$item['goods_number']){
                    continue;
                }
                //之前库存-入库
                $before_stock_in = $gsModel->getStockValue(["warehouse_id"=>$data['in_warehouse_id'],"goods_id"=>$item['goods_id'],"color_id"=>$item['color_id'],"size_id"=>$item['size_id']],'stock_number');
                //之前库存-出库
                $before_stock_out = $gsModel->getStockValue(["warehouse_id"=>$data['out_warehouse_id'],"goods_id"=>$item['goods_id'],"color_id"=>$item['color_id'],"size_id"=>$item['size_id']],'stock_number');

                //调拨单详情
                $detailsData[] = [
                    "orders_id"=>$data['orders_id'],
                    "orders_code"=>$data['orders_code'],
                    "goods_code"=>$item['goods_code'],
                    "goods_id"=>$item['goods_id'],
                    "color_id"=>$item['color_id'],
                    "size_id"=>$item['size_id'],
                    "goods_number"=>$item['goods_number'],
                    "out_warehouse_number"=>$before_stock_out ? $before_stock_out : 0,//调出仓库 当前数量
                    "in_warehouse_number"=>$before_stock_in ? $before_stock_in : 0,//调入仓库 当前数量
                    "goods_status"=>$item['goods_status'],//商品状态
                    "com_id"=>$data['com_id'],
                ];
            }
            //删除原详情数据
            if(!$todModel->where("orders_id","=",$order['orders_id'])->delete()){
                throw new Exception("删除原详情数据失败");
            }

            //插入详情数据
            if(!$todModel->saveAll($detailsData)){
                throw new Exception("保存调拨单详情失败");
            }

            //出入库操作
            $res = $this->outInExe($order,$details);
            if(!$res){
                throw new Exception($this->error);
            }

            $this->commit();
            return true;
        }catch(Exception $e){
            $this->rollback();
            $this->error = $e->getMessage();
            return false;
        }

    }

    /**
     * 调拨单出入库操作
     * zll
     * Date: 2021/5/22
     * @param $data 调拨单基础信息
     * @param $details 调拨单详情数组
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function outInExe($data,$details){
        //库存模型
        $gsModel = new GoodsStockModel();
        //库存日志模型
        $sdModel = new StockDiary();

        //拼接更新 入库存数据
        $stock_data = [];
        //拼接插入 入库数据
        $stock_data_in = [];
        //入库记录
        $stock_data_in_log = [];

        //拼接更新 出库数据
        $stock_data_out = [];
        //出库记录
        $stock_data_out_log = [];

        foreach ($details as $key=>$item){
            //之前库存-入库
            $before_stock_in = $gsModel->getStockValue(["warehouse_id"=>$data['in_warehouse_id'],"goods_id"=>$item['goods_id'],"color_id"=>$item['color_id'],"size_id"=>$item['size_id']],'stock_number');
            //之前库存-出库
            $before_stock_out = $gsModel->getStockValue(["warehouse_id"=>$data['out_warehouse_id'],"goods_id"=>$item['goods_id'],"color_id"=>$item['color_id'],"size_id"=>$item['size_id']],'stock_number');

            //查询调入仓库中是否有该商品,有则更新库存,无则入库
            $isExistGoods = $gsModel
                ->where("warehouse_id","=",$data['in_warehouse_id'])
                ->where("goods_id","=",$item['goods_id'])
                ->where("color_id","=",$item['color_id'])
                ->where("size_id","=",$item['size_id'])
                ->find();
//            dump($gsModel->getLastSql());
            //入仓库 操作库存数据
            if(!$isExistGoods){//插入库存数据
                $stock_data_in[] = [
                    "warehouse_id"=>$data['in_warehouse_id'],
                    "goods_id"=>$item['goods_id'],
                    "goods_code"=>$item['goods_code'],
                    "color_id"=>$item['color_id'],
                    "size_id"=>$item['size_id'],
                    "stock_number"=>$item['goods_number'] ?:0,
                    "lock_version"=>1,
                    "last_orders_code"=>$data['orders_code'],
                    "com_id"=>$data['com_id'],
                    "shop_id"=>1,//$item['shop_id'],
                ];
            }else{//更新库存
                $stock_data[] = [
                    "warehouse_id"=>$data['in_warehouse_id'],
                    "goods_id"=>$item['goods_id'],
                    "color_id"=>$item['color_id'],
                    "size_id"=>$item['size_id'],
                    "stock_number"=>Db::raw("stock_number+".$item['goods_number'] ?:0),
                    "last_orders_code"=>$data['orders_code'],
                    "com_id"=>$data['com_id'],
                    "shop_id"=>1,//$item['shop_id'],
                ];
            }

            //入仓库 记录数据
            $stock_data_in_log[] = [
                "warehouse_id"=>$data['in_warehouse_id'],
                "orders_code"=>$data['orders_code'],
                "goods_id"=>$item['goods_id'],
                "color_id"=>$item['color_id'],
                "size_id"=>$item['size_id'],
                "goods_number"=>$item['goods_number'],
                "stock_number"=>$before_stock_in ?: 0,
                "orders_type"=>'调拨单-入库',
                "goods_code"=>$item['goods_code'],
                "com_id"=>$data['com_id'],//企业id
                "shop_id"=>1,//$oditem['shop_id'],//企业id
            ];

            //出仓库 操作库存数据
            $stock_data_out[] = [
                "warehouse_id"=>$data['out_warehouse_id'],
                "goods_id"=>$item['goods_id'],
                "color_id"=>$item['color_id'],
                "size_id"=>$item['size_id'],
                "stock_number"=>Db::raw("stock_number-".$item['goods_number']),
                "last_orders_code"=>$data['orders_code'],
                "com_id"=>$data['com_id'],
                "shop_id"=>1,//$item['shop_id'],
            ];

            //出仓库 记录数据
            $stock_data_out_log[] = [
                "warehouse_id"=>$data['out_warehouse_id'],
                "orders_code"=>$data['orders_code'],
                "goods_id"=>$item['goods_id'],
                "color_id"=>$item['color_id'],
                "size_id"=>$item['size_id'],
                "goods_number"=>$item['goods_number']*-1,
                "stock_number"=>$before_stock_out ?: 0,
                "orders_type"=>'调拨单-出库',
                "goods_code"=>$item['goods_code'],
                "com_id"=>$data['com_id'],//企业id
                "shop_id"=>1,//$oditem['shop_id'],//企业id
            ];


        }
//        dump("拼接更新 入库存数据");
//        dump($stock_data);
//        dump("拼接插入 入库存数据");
//        dump($stock_data_in);
//        dump("入库记录");
//        dump($stock_data_in_log);
//        dump("拼接更新 出库数据");
//        dump($stock_data_out);
//        dump("出库记录");
//        dump($stock_data_out_log);die;

        if(!empty($stock_data)){
            if(!$gsModel->updateForInventory($stock_data)){
                $this->error = "调拨单更新入库存失败";
                return false;
            }
        }
        if(!$gsModel->saveAll($stock_data_in)){
            $this->error = "调拨单插入入库存失败";
            return false;
        }
        if(!$sdModel->saveAll($stock_data_in_log)){
            $this->error = "调拨单插入-入库库存日志失败";
            return false;
        }
        if(!empty($stock_data_out)){
            if(!$gsModel->updateForInventory($stock_data_out)){
                $this->error = "调拨单更新出库库存失败";
                return false;
            }
        }
        if(!$sdModel->saveAll($stock_data_out_log)){
            $this->error = "调拨单插入-出库库存日志失败";
            return false;
        }

        return true;
    }
}