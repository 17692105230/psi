<?php
/**
 * Created by PhpStorm.
 * User: zll
 * Date: 2021/6/10
 * Time: 13:53
 */

namespace app\web\controller;

//收银台控制器
use app\common\utils\BuildCode;
use app\web\model\GoodsStock as GoodsStockModel;
use app\web\model\SaleOrders as SaleOrdersModel;
use app\web\model\SaleOrders;
use app\web\model\SaleOrdersDetails as SaleOrdersDetailsModel;
use app\web\validate\SaleOrders as SaleOrdersValidate;
use think\Exception;
use think\facade\Db;
use think\Request;
use app\web\model\MemberBase as MemberBaseModel;
use app\web\model\MemberAccount as MemberAccountModel;
use app\web\model\Organization as OrganizationModel;
use app\web\model\Goods as GoodsModel;
use app\web\model\User as UserModel;
use app\web\model\MemberIntegralDetails as MemberIntegralDetailsModel;
use app\web\model\StockDiary as StockDiaryModel;

class Cashier extends Controller
{
    //收银台提交
    public function submit(Request $request)
    {
        $data = $request->post();
        $nowTime = time();
        $memberBaseModel = new MemberBaseModel();               //会员基础信息模型
        $memberAccountModel = new MemberAccountModel();         //会员账户模型
//        $orgModel = new OrganizationModel();                  //组织机构模型
        $saleOrdersModel = new SaleOrdersModel();               //销售单基础信息模型
        $saleOrdersDetailsModel = new SaleOrdersDetailsModel(); //销售单详情模型
        $goodsStockModel = new GoodsStockModel();               //库存模型
        $stockDiaryModel = new StockDiaryModel();               //库存明细模型
        $userModel = new UserModel();                           //用户模型
        $midModel = new MemberIntegralDetailsModel();           //会员积分明细模型

        //赋值销售员id
        $data['salesman_id'] = $this->user_id;

        //单据编号
        $orders_code = BuildCode::dateCode("SOL");
        //数据验证
        $saleOrdersValidate = new SaleOrdersValidate();
        if (!$saleOrdersValidate->scene("add")->check($data)) {
            return $this->renderError($saleOrdersValidate->getError());
        }

        $commonWhere = ["com_id" => $this->com_id];

        //获取会员基础信息
        $memberData = $memberBaseModel->getOne(array_merge(
            $commonWhere,
            [
                "member_id" => $data['client_id'],
                "member_status" => 1
            ]
        ));
        if (!$memberData) {
            return $this->renderError("会员不存在或已冻结");
        }
        //获取会员账户
        $memberAccountData = $memberAccountModel->where(["member_code" => $memberData['member_code']])->find();
        //获取门店
//        $warehouse = $orgModel->getOne(["org_id"=>$data['warehouse_id']]);

        //获取用户(销售员)
        $user = $userModel->getOne(array_merge($commonWhere, ["user_id" => $data['salesman_id']]));

        //整理销售单详情信息
        $detailList = isJson($data['details'], true);
        if(!$detailList){
            return $this->renderError("单据详情数据异常");
        }

        //整理销售单基本信息
        $orderData = [
            "orders_code" => $orders_code,
            "member_id" => $data['client_id'],//会员id
            "warehouse_id" => $data['warehouse_id'],//门店id
            "settlement_id" => $data['settlement_id'],//结算账户id
            "orders_pmoney" => $data['orders_pmoney'],//应收金额
            "orders_rmoney" => $data['orders_rmoney'],//实收金额
            "goods_number" => $data['goods_number'],//商品数量
            "erase_money" => $data['erase_money'],//抹零金额
            "profit" => $this->countProfit($detailList),//毛利
            "salesman_id" => $data['salesman_id'],//销售员id
            "user_id" => $data['salesman_id'],//制单人id
            "orders_status" => 9,//单据状态0草稿9正式
            "orders_date" => $nowTime,//单据时间
            "create_time" => $nowTime,//创建时间
            "update_time" => $nowTime,//更新时间
            "com_id" => $this->com_id,//企业id
            "orders_type" => 1,//单据类型0销售单1零售单
        ];
        //启动事务
        $memberBaseModel->startTrans();

        try {

            //新增销售单基本信息
            $orders_id = $saleOrdersModel->insertGetId($orderData);
            if (!$orders_id) {
                throw new \Exception("基础新增失败", 501);
            }

            //存储单据详情
            $detailData = [];
            //存储库存数据
            $stockData = [];
            //库存明细
            $stockDiary = [];
            foreach ($detailList as $v) {
                //之前库存-入库
                $before_stock = $goodsStockModel
                    ->getStockValue(
                        [
                            "warehouse_id"=>$data['warehouse_id'],
                            "goods_id"=>$v['goods_id'],
                            "color_id"=>$v['color_id'],
                            "size_id"=>$v['size_id']
                        ],
                        'stock_number'
                    );
                //构造销售单详情数组
                $detailData[] = [
                    "orders_id" => $orders_id,//单据id
                    "orders_code" => $orders_code,//单据编号
                    "goods_code" => $v['goods_code'],//商品编号
                    "goods_id" => $v['goods_id'],//商品id
                    "color_id" => $v['color_id'],//颜色id
                    "size_id" => $v['size_id'],//尺码id
                    "goods_number" => $v['goods_number'],//数量
                    "goods_price" => $v['goods_price'],//单价
                    "goods_tmoney" => bcmul($v['goods_price'] , $v['goods_number']),//合计金额
                    "goods_discount" => 0,//折扣
                    "goods_daprice" => 0,//折后价
                    "goods_tdamoney" => 0,//折后金额
                    "goods_status" => 1,//$v['goods_status'],//商品状态
                    "create_time" => $nowTime,//
                    "update_time" => $nowTime,//
                    "com_id" => $this->com_id,//企业id
                ];
                //构造减库存数组
                $stockData[] = [
                    "warehouse_id" => $data['warehouse_id'],
                    "goods_id" => $v['goods_id'],
                    "color_id" => $v['color_id'],
                    "size_id" => $v['size_id'],
                    "stock_number" => Db::raw("stock_number-" . $v['goods_number']),
                    "last_orders_code" => $orders_code,
                    "com_id" => $this->com_id,
                    "shop_id" => 0,
                ];
                //构造库存明细
                $stockDiary[] = [
                    "warehouse_id"=>$data['warehouse_id'],
                    "orders_code"=>$orders_code,
                    "goods_id"=>$v['goods_id'],
                    "color_id"=>$v['color_id'],
                    "size_id"=>$v['size_id'],
                    "goods_number"=>$v['goods_number']*-1,
                    "stock_number"=>$before_stock ?: 0,
                    "orders_type"=>'零售单-出库',
                    "goods_code"=>$v['goods_code'],
                    "com_id"=>$this->com_id,//企业id
                ];
            }

            // 更新会员_账户信息
            $account_update = [
                'total_sum_original' => $memberAccountData['total_sum_original'] + $data['orders_pmoney'], // 购物原价总额
                'total_sum' => $memberAccountData['total_sum'] + $data['orders_rmoney'], // 购物总额
                'total_orders_count' => $memberAccountData['total_orders_count'] + 1, // 订单总数
                'update_time' => time()
            ];
            $account_is_update = $memberAccountModel->update($account_update, ['id' => $memberAccountData['id']]);
            if (!$account_is_update) {
                throw new \Exception("账户信息更新失败", 501);
            }

            //计算单子总积分
            $points = $this->getIntegralByGoodsList($detailList);

            //积分明细数据
            $integral_details_data = [
                "sales_openid" => $data['salesman_id'],//导购员OpenID,先用user_id吧
                "sales_name" => $user['user_name'],//导购员姓名
                "cardid" => 0,//会员卡类型ID
                "usercardcode" => "",//会员卡编号
                "member_name" => $memberData['member_name'],//会员姓名
                "member_before_integral" => $memberAccountData['account_points'],//会员之前积分
                "member_current_integral" => $points,//会员获得积分
                "member_after_integral" => $memberAccountData['account_points'] + $points,//会员之后积分
                "create_time" => $nowTime,//
                "commodity_remark" => "",//备注
                "member_openid" => $memberData['member_id'],//会员OpenID,先用会员base的member_id吧
                "member_account_id" => $memberAccountData['id'],//会员账户id
            ];

            //增加销售单详情
            if (!$saleOrdersDetailsModel->saveAll($detailData)) {
                throw new \Exception("详情新增失败", 502);
            }
            //操作库存
            $goodsStockModel->updateForInventory($stockData);

            //新增库存明细
            if(!$stockDiaryModel->saveAll($stockDiary)){
                throw new \Exception("出库明细错误",506);
            }

            //会员积分增加
            if (!$memberAccountData->save(['account_points'=>Db::raw("account_points+".$points)])) {
                throw new \Exception("增加积分错误", 503);
            }
            //新增积分明细
            if (!$midModel->insert($integral_details_data)) {
                throw new \Exception("积分明细错误", 505);
            }

            $memberBaseModel->commit();

            //查询会员最新信息
            $new_member_info = $memberBaseModel->where("member_id","=",$data['client_id'])->with(["accounts"])->find();
            return $this->renderSuccess(['now_points'=>$points,'member_info'=>$new_member_info->toArray()], "销售完成");
        } catch (\Exception $e) {
            $memberBaseModel->rollback();
            return $this->renderError("销售失败-".$e->getMessage()."-".$e->getCode()."-".$e->getLine());
        }
    }

    //根据商品列表获得总积分
    public function getIntegralByGoodsList($detailList)
    {
        $sum_integral = 0;
        $goodsModel = new GoodsModel();

        //筛选出商品id
        $goods_ids = array_map(function ($v) {
            return $v['goods_id'];
        }, $detailList);
        //去重
        $goods_ids = array_unique($goods_ids);

        $goodsList = $goodsModel->where("goods_id", "in", $goods_ids)->column("*", "goods_id");

        foreach ($detailList as $v) {
            $sum_integral += $goodsList[$v['goods_id']]['integral'] * $v['goods_number'];
        }

        return $sum_integral;
    }

    //计算毛利
    public function countProfit($details){
//        $details = $details;
        $goods_ids = array_column($details, 'goods_id');
        $goodsModel = new GoodsModel();
        $goodsList = array_column(json_decode($goodsModel->whereIn('goods_id', $goods_ids)->select(), true), null, 'goods_id');
        $profit = 0; // 毛利
        foreach ($details as $detail) {
            $goods = $goodsList[$detail['goods_id']];
            $profit += ($detail['goods_price'] - $goods['goods_pprice']) * $detail['goods_number'];
        }
        return $profit;
    }

    //获取收银台基础信息
    public function getShopInfo(){
        $org_id = $this->request->get("org_id",0);
        $data = [];
        $userModel = new UserModel();
        $orgModel = new OrganizationModel();
        $saleOrdersModel = new SaleOrders();
        //获取门店列表
        $org_list = $orgModel
            ->where("com_id","=",$this->com_id)
            ->where("org_status","=",1)
//            ->where("org_type","in",[1,2])
            ->select();
        if(!$org_list){
            return $this->renderError("请先添加店仓");
        }
        //用户默认所属第一个门店
        $data['org_id'] = !$org_id ? $org_list[0]['org_id'] : $org_id;
        //获取用户所属门店
        $user = $userModel->where("user_id","=",$this->user_id)->find();
        if(!$user){
            return $this->renderError("用户不存在");
        }
        if($user['org_id']){//如果用户有所属门店,则默认分配到所属的第一个门店
            $org_ids = substr($user['org_id'],1,(mb_strlen($user['org_id'])-2));
            $arr_org_id = explode(",",$org_ids);
            $data['org_id'] = $arr_org_id[0];
        }

        //获取门店总销售额
        $data['sum_money'] = $saleOrdersModel
            ->where("com_id","=",$this->com_id)
            ->where("warehouse_id","=",$data['org_id'])
            ->where("orders_type","=",1)
            ->sum("orders_rmoney");
        return $this->renderSuccess($data);
    }
}