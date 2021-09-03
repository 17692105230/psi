<?php
namespace app\web\controller;

use app\common\model\web\GoodsStock;
use app\web\model\StockDiary as StockDiaryModel;
use app\web\validate\StoreValidate;
use think\exception\ValidateException;
use app\web\model\Organization as OrganizationModel;
use app\web\model\Goods as GoodsModel;

/**
 * 库存管理
 * Class Store
 * @package app\web\controller
 */
class Store extends Controller
{
	public function loadGoodsList()
	{
		return json(json_decode('{"total":30018,"rows":[{"stock_id":171163,"goods_id":10005,"goods_code":"10010000","goods_name":"衣服10000","goods_barcode":"20010000","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":21,"warehouse_name":"二车间仓库","unit_name":"套","stock_number":"300","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517324482,"color_names":"黑色,花白色","size_names":"M,L"},{"stock_id":12,"goods_id":10005,"goods_code":"10010000","goods_name":"衣服10000","goods_barcode":"20010000","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":20,"warehouse_name":"丹尼斯店仓库","unit_name":"套","stock_number":"46","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517324482,"color_names":"黑色,花白色","size_names":"M,L"},{"stock_id":105624,"goods_id":10005,"goods_code":"10010000","goods_name":"衣服10000","goods_barcode":"20010000","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":19,"warehouse_name":"办公室仓库","unit_name":"套","stock_number":"404","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517324482,"color_names":"黑色,花白色","size_names":"M,L"},{"stock_id":135183,"goods_id":1010,"goods_code":"1001005","goods_name":"衣服1005","goods_barcode":"2001005","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":21,"warehouse_name":"二车间仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323843,"color_names":"花白色,黑色","size_names":"L,M"},{"stock_id":4113,"goods_id":1010,"goods_code":"1001005","goods_name":"衣服1005","goods_barcode":"2001005","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":20,"warehouse_name":"丹尼斯店仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323843,"color_names":"花白色,黑色","size_names":"L,M"},{"stock_id":69648,"goods_id":1010,"goods_code":"1001005","goods_name":"衣服1005","goods_barcode":"2001005","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":19,"warehouse_name":"办公室仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323843,"color_names":"花白色,黑色","size_names":"L,M"},{"stock_id":135179,"goods_id":1009,"goods_code":"1001004","goods_name":"衣服1004","goods_barcode":"2001004","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":21,"warehouse_name":"二车间仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323843,"color_names":"花白色,黑色","size_names":"L,M"},{"stock_id":4109,"goods_id":1009,"goods_code":"1001004","goods_name":"衣服1004","goods_barcode":"2001004","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":20,"warehouse_name":"丹尼斯店仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323843,"color_names":"花白色,黑色","size_names":"L,M"},{"stock_id":69644,"goods_id":1009,"goods_code":"1001004","goods_name":"衣服1004","goods_barcode":"2001004","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":19,"warehouse_name":"办公室仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323843,"color_names":"花白色,黑色","size_names":"L,M"},{"stock_id":135175,"goods_id":1008,"goods_code":"1001003","goods_name":"衣服1003","goods_barcode":"2001003","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":21,"warehouse_name":"二车间仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323843,"color_names":"黑色,花白色","size_names":"L,M"},{"stock_id":4105,"goods_id":1008,"goods_code":"1001003","goods_name":"衣服1003","goods_barcode":"2001003","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":20,"warehouse_name":"丹尼斯店仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323843,"color_names":"黑色,花白色","size_names":"L,M"},{"stock_id":135187,"goods_id":1011,"goods_code":"1001006","goods_name":"衣服1006","goods_barcode":"2001006","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":21,"warehouse_name":"二车间仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323843,"color_names":"花白色,黑色","size_names":"L,M"},{"stock_id":69640,"goods_id":1008,"goods_code":"1001003","goods_name":"衣服1003","goods_barcode":"2001003","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":19,"warehouse_name":"办公室仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323843,"color_names":"黑色,花白色","size_names":"L,M"},{"stock_id":4117,"goods_id":1011,"goods_code":"1001006","goods_name":"衣服1006","goods_barcode":"2001006","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":20,"warehouse_name":"丹尼斯店仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323843,"color_names":"花白色,黑色","size_names":"L,M"},{"stock_id":69652,"goods_id":1011,"goods_code":"1001006","goods_name":"衣服1006","goods_barcode":"2001006","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":19,"warehouse_name":"办公室仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323843,"color_names":"花白色,黑色","size_names":"L,M"},{"stock_id":69636,"goods_id":1007,"goods_code":"1001002","goods_name":"衣服1002","goods_barcode":"2001002","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":19,"warehouse_name":"办公室仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323842,"color_names":"黑色,花白色","size_names":"M,L"},{"stock_id":135171,"goods_id":1007,"goods_code":"1001002","goods_name":"衣服1002","goods_barcode":"2001002","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":21,"warehouse_name":"二车间仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323842,"color_names":"黑色,花白色","size_names":"M,L"},{"stock_id":4101,"goods_id":1007,"goods_code":"1001002","goods_name":"衣服1002","goods_barcode":"2001002","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":20,"warehouse_name":"丹尼斯店仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323842,"color_names":"黑色,花白色","size_names":"M,L"},{"stock_id":135167,"goods_id":1006,"goods_code":"1001000","goods_name":"衣服1000","goods_barcode":"2001000","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":21,"warehouse_name":"二车间仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323687,"color_names":"黑色,花白色","size_names":"M,L"},{"stock_id":4097,"goods_id":1006,"goods_code":"1001000","goods_name":"衣服1000","goods_barcode":"2001000","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":20,"warehouse_name":"丹尼斯店仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323687,"color_names":"黑色,花白色","size_names":"M,L"},{"stock_id":69632,"goods_id":1006,"goods_code":"1001000","goods_name":"衣服1000","goods_barcode":"2001000","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":19,"warehouse_name":"办公室仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323687,"color_names":"黑色,花白色","size_names":"M,L"},{"stock_id":131567,"goods_id":106,"goods_code":"100100","goods_name":"衣服100","goods_barcode":"200100","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":21,"warehouse_name":"二车间仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323626,"color_names":"黑色,花白色","size_names":"M,L"},{"stock_id":497,"goods_id":106,"goods_code":"100100","goods_name":"衣服100","goods_barcode":"200100","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":20,"warehouse_name":"丹尼斯店仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323626,"color_names":"黑色,花白色","size_names":"M,L"},{"stock_id":66032,"goods_id":106,"goods_code":"100100","goods_name":"衣服100","goods_barcode":"200100","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":19,"warehouse_name":"办公室仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323626,"color_names":"黑色,花白色","size_names":"M,L"},{"stock_id":137,"goods_id":16,"goods_code":"10010","goods_name":"衣服10","goods_barcode":"20010","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":20,"warehouse_name":"丹尼斯店仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323620,"color_names":"花白色,黑色","size_names":"M,L"},{"stock_id":65672,"goods_id":16,"goods_code":"10010","goods_name":"衣服10","goods_barcode":"20010","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":19,"warehouse_name":"办公室仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323620,"color_names":"花白色,黑色","size_names":"M,L"},{"stock_id":131207,"goods_id":16,"goods_code":"10010","goods_name":"衣服10","goods_barcode":"20010","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":21,"warehouse_name":"二车间仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517323620,"color_names":"花白色,黑色","size_names":"M,L"},{"stock_id":131167,"goods_id":6,"goods_code":"1000000000001","goods_name":"一条裤子","goods_barcode":"1000000000001","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":21,"warehouse_name":"二车间仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517147883,"color_names":"花白色,黑色","size_names":"L,M"},{"stock_id":97,"goods_id":6,"goods_code":"1000000000001","goods_name":"一条裤子","goods_barcode":"1000000000001","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":20,"warehouse_name":"丹尼斯店仓库","unit_name":"套","stock_number":"390","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517147883,"color_names":"花白色,黑色","size_names":"L,M"},{"stock_id":65632,"goods_id":6,"goods_code":"1000000000001","goods_name":"一条裤子","goods_barcode":"1000000000001","color_ids":"4,10","size_ids":"4,5","category_id":"3","category_name":"T恤","warehouse_id":19,"warehouse_name":"办公室仓库","unit_name":"套","stock_number":"396","brand_name":"玛斯菲尔","brand_id":"3","goods_year":2018,"goods_season":1,"goods_llimit":2,"goods_ulimit":2,"create_time":1517147883,"color_names":"花白色,黑色","size_names":"L,M"}]}'));
	}

	public function loadDetailsGoods()
	{
		return json(json_decode('[{"stock_id":73,"warehouse_id":20,"goods_code":"G1801260000001","color_id":"10","size_id":"4","stock_number":87,"lock_version":0,"update_time":1526260527,"last_orders_code":null,"color_name":"花白色","size_name":"M"},{"stock_id":74,"warehouse_id":20,"goods_code":"G1801260000001","color_id":"10","size_id":"5","stock_number":87,"lock_version":0,"update_time":1526260527,"last_orders_code":null,"color_name":"花白色","size_name":"L"},{"stock_id":75,"warehouse_id":20,"goods_code":"G1801260000001","color_id":"10","size_id":"6","stock_number":87,"lock_version":0,"update_time":1526260527,"last_orders_code":null,"color_name":"花白色","size_name":"XL"},{"stock_id":76,"warehouse_id":20,"goods_code":"G1801260000001","color_id":"10","size_id":"7","stock_number":87,"lock_version":0,"update_time":1526260527,"last_orders_code":null,"color_name":"花白色","size_name":"2XL"},{"stock_id":89,"warehouse_id":20,"goods_code":"G1801260000001","color_id":"2","size_id":"4","stock_number":87,"lock_version":0,"update_time":1526260527,"last_orders_code":null,"color_name":"红色","size_name":"M"},{"stock_id":90,"warehouse_id":20,"goods_code":"G1801260000001","color_id":"2","size_id":"5","stock_number":87,"lock_version":0,"update_time":1526260527,"last_orders_code":null,"color_name":"红色","size_name":"L"},{"stock_id":91,"warehouse_id":20,"goods_code":"G1801260000001","color_id":"2","size_id":"6","stock_number":87,"lock_version":0,"update_time":1526260527,"last_orders_code":null,"color_name":"红色","size_name":"XL"},{"stock_id":92,"warehouse_id":20,"goods_code":"G1801260000001","color_id":"2","size_id":"7","stock_number":87,"lock_version":0,"update_time":1526260527,"last_orders_code":null,"color_name":"红色","size_name":"2XL"},{"stock_id":93,"warehouse_id":20,"goods_code":"G1801260000001","color_id":"3","size_id":"4","stock_number":87,"lock_version":0,"update_time":1526260527,"last_orders_code":null,"color_name":"蓝色","size_name":"M"},{"stock_id":94,"warehouse_id":20,"goods_code":"G1801260000001","color_id":"3","size_id":"5","stock_number":87,"lock_version":0,"update_time":1526260527,"last_orders_code":null,"color_name":"蓝色","size_name":"L"},{"stock_id":95,"warehouse_id":20,"goods_code":"G1801260000001","color_id":"3","size_id":"6","stock_number":87,"lock_version":0,"update_time":1526260527,"last_orders_code":null,"color_name":"蓝色","size_name":"XL"},{"stock_id":96,"warehouse_id":20,"goods_code":"G1801260000001","color_id":"3","size_id":"7","stock_number":87,"lock_version":0,"update_time":1526260527,"last_orders_code":null,"color_name":"蓝色","size_name":"2XL"}]'));
	}

	public function loadWarehouseList()
	{
		return json(json_decode('[{"org_id":19,"org_pid":2,"org_name":"鹰城世贸仓库","org_head":"张志超","org_phone":"15836986691","org_type":2,"org_status":1,"org_sort":100,"lock_version":17,"update_time":1589897831},{"org_id":20,"org_pid":23,"org_name":"丹尼斯店仓库","org_head":"张志超","org_phone":"15836986691","org_type":2,"org_status":1,"org_sort":100,"lock_version":7,"update_time":1522574170},{"org_id":21,"org_pid":24,"org_name":"东安路仓库","org_head":"张志超","org_phone":"15836986691","org_type":2,"org_status":1,"org_sort":100,"lock_version":2,"update_time":1522573817},{"org_id":22,"org_pid":24,"org_name":"黄楝树仓库","org_head":"张志超","org_phone":"15836986691","org_type":2,"org_status":1,"org_sort":100,"lock_version":2,"update_time":1522573817},{"org_id":23,"org_pid":24,"org_name":"郑州华联仓库","org_head":"张志超","org_phone":"15836986691","org_type":2,"org_status":1,"org_sort":100,"lock_version":2,"update_time":1522573817}]'));
	}

    /**
     * 商品库存流水查询
     * zll
     * Date: 2021/5/24
     * Time: 13:59
     * @return array
     */
	public function queryRecordGoods(){
        $data = $this->request->get();
        $data['com_id'] = $this->com_id;
        $storeV = new StoreValidate();
        $checkRes = $storeV->scene("search")->check($data);
        if(!$checkRes){
            return $this->renderError($storeV->getError());
        }
        $model = new StockDiaryModel();
        $list = $model->searchDiarys($data);

        return $this->renderSuccess($list);
    }

    /**
     * 店仓分析
     * zll
     * Date: 2021/5/24
     * Time: 17:12
     */
    public function analyseStock(){
        //查出仓库列表
        $orgModel = new OrganizationModel();
        $orgList = $orgModel->select()->toArray();
        //遍历列表 查出库存数量
        foreach ($orgList as $K=>$org){
            $gsModel = new GoodsStock();
            //库存列表
            $gsList = $gsModel
                ->where("warehouse_id","=",$org['org_id'])
                ->with(["goods"])
                ->select()->toArray();

            $sum = 0;
            $sum_price = 0;
            foreach ($gsList as $goods_stock){
                $sum += $goods_stock['stock_number'];
                if(!empty($goods_stock['goods'])){
                    $sum_price += $goods_stock['stock_number'] * $goods_stock['goods']['goods_pprice'];
                }
            }
            $orgList[$K]['sum'] = $sum;
            $orgList[$K]['sum_price'] = $sum_price;
        }
        return $this->renderSuccess($orgList);
    }

    /**
     * 商品分析
     * zll
     * Date: 2021/5/24
     * Time: 17:12
     */
    public function analyseGoods(){
        $goodsModle = new GoodsModel();
        $gsModel = new GoodsStock();
        //获取商品列表
        $goodsList = $goodsModle->select()->toArray();
        //遍历商品,查询商品库存总量
        foreach ($goodsList as $k => $goods) {
            $sum = $gsModel->where("goods_id","=",$goods['goods_id'])->sum("stock_number");
            //计算商品总价格
            $sum_price = $sum * $goods['goods_pprice'];
            $goodsList[$k]['sum'] = $sum;
            $goodsList[$k]['sum_price'] = $sum_price;
        }
        return $this->renderSuccess($goodsList);
    }

    //库存查询
    public function storeSearch(){
        //查出符合条件的商品列表
        $data = $this->request->get();
        $model = new GoodsStock();
        $list = $model->searchGoodsStock($data);
    }
}