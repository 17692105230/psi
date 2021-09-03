<?php


namespace app\web\controller;


use app\common\utils\BuildCode;
use app\Request;
use app\web\model\GoodsStock as GoodsStockModel;
use app\web\validate\PurchaseOrders as PurchaseOrdersValidate;
use app\web\model\PurchaseOrders as PurchaseOrdersModel;
use app\web\model\PurchaseOrdersDetails as PurchaseOrdersDetailsModel;
use think\facade\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use app\web\model\Attachment as AttachModel;
use app\web\model\GoodsAssist as GoodsAssistModel;

class PurchaseOrders extends Controller
{
    /**
     * 函数描述:获取采购单列表
     * Date: 2021/1/9
     * Time: 17:30
     * @author mll
     */
    public function getPurchaseOrders(Request $request)
    {
        $data = $request->get();
        $model = new PurchaseOrdersModel();
        $where['com_id'] = $this->com_id;
        $page = $request->get('page', 1);
        $rows = $request->get('rows', 20);
        if ($data['orders_status'] != -1) {
            $where['orders_status'] = (int)$data['orders_status'];
        }
        $res = $model->getPurchaseList($where, $page, $rows);
        if (!$res) {
            return $this->renderError('查询失败');
        }
        return $this->renderSuccess($res, '查询成功');
    }

    /**
     * 函数描述:添加采购单
     * @param Request $request
     * Date: 2021/1/11
     * Time: 9:43
     * @return array
     * @author mll
     */
    public function savePurchaseOrders(Request $request)
    {
        $data = $request->post();
        $purchaseModel = new PurchaseOrdersModel();
        $purchaseValidate = new PurchaseOrdersValidate();
        if (!$purchaseValidate->scene('add')->check($data)) {
            return $this->renderError($purchaseValidate->getError());
        }
        $data['com_id'] = $this->com_id;
        $res = $purchaseModel->saveOrders($data, $this->user);
        if (!$res) {
            return $this->renderError($purchaseModel->getError());
        }
        return $this->renderSuccess('', '添加成功');
    }

    /**
     * 函数描述:查询详情
     * @param Request $request
     * Date: 2021/1/11
     * Time: 16:06
     * @author mll
     */
    public function getOrdersInfo(Request $request)
    {
        $data = $request->get();
        $purchaseModel = new PurchaseOrdersModel();
        $where = [
            'orders_code' => $data['orders_code'],
            'com_id' => $this->com_id
        ];
        $res = $purchaseModel->getOrderInfo($where);
        $arr = $res->toArray();

        $details = $arr['details'];
        $goods_ids = array_column($details, 'goods_id');
        // 查询库存
        $stockModel = new GoodsStockModel();
        $stock_list = json_decode($stockModel->getListByWhereIn('goods_id', $goods_ids), true);
        $goodsAssistModel = new GoodsAssistModel();
        $temp1 = $temp = [];
        foreach ($details as $key => $val) {
            $goods_id = $val['goods_id'];
            $color_id = $val['color_id'];
            $size_id = $val['size_id'];
            $img = json_decode($goodsAssistModel->where('goods_id', '=', $goods_id)->order('assist_sort ASC')->select(), true);
            $images = array_map(function ($v) {
                $v['assist_url'] = "http://" . $v['assist_url'];
                return $v;
            }, $img);
            $total_stockQuantity = array_sum(array_filter(array_map(function ($stock) use ($goods_id) {
                if ($stock['goods_id'] == $goods_id) {
                    return $stock['stock_number'];
                }
            }, $stock_list)));
            $stockQuantity = array_sum(array_filter(array_map(function ($stock) use ($goods_id, $color_id) {
                if ($stock['goods_id'] == $goods_id && $color_id == $stock['color_id']) {
                    return $stock['stock_number'];
                }
            }, $stock_list)));
            $stockQuantity1 = array_sum(array_filter(array_map(function ($stock) use ($goods_id, $color_id, $size_id) {
                if ($stock['goods_id'] == $goods_id && $color_id == $stock['color_id'] && $size_id == $stock['size_id']) {
                    return $stock['stock_number'];
                }
            }, $stock_list)));
            if (isset($temp[$val['goods_code']])) {
                $temp[$val['goods_code']]['inputQuantity'] += $val['goods_number'];
                // 有这个颜色必定没有这个尺寸 直接添加
                if (isset($temp[$val['goods_code']]['list'][$val['color_id']])) {
                    // 添加尺寸
                    array_push($temp[$val['goods_code']]['list'][$val['color_id']]['sizeList'], [
                        'size_id' => $val['size_id'],
                        'size_name' => $val['size_name'],
                        'inputQuantity' => $val['goods_number'],
                        'stockQuantity' => $stockQuantity1,
                    ]);
                    $temp[$val['goods_code']]['list'][$val['color_id']]['inputQuantity'] += $val['goods_number'];

                } else { // 没有这个颜色必然没有尺度 添加第一个该颜色下第一个尺寸
                    $temp[$val['goods_code']]['list'][$val['color_id']] = [
                        'color_id' => $val['color_id'],
                        'color_name' => $val['color_name'],
                        'inputQuantity' => $val['goods_number'],
                        'stockQuantity' => $stockQuantity,
                        'sizeList' => [
                            [
                                'size_id' => $val['size_id'],
                                'size_name' => $val['size_name'],
                                'inputQuantity' => $val['goods_number'],
                                'stockQuantity' => $stockQuantity1,
                            ]
                        ],
                    ];

                }
            } else {
                $temp[$val['goods_code']] = [
                    'goods_code' => $val['goods_code'],
                    'goods_name' => $val['goods_name'],
                    'goods_id' => $val['goods_id'],
                    'goods_price' => $val['goods_price'],
                    'goods_tmoney' => $val['goods_tmoney'],
                    'images' => $images,
                    'inputQuantity' => $val['goods_number'],
                    'stockQuantity' => $total_stockQuantity,
                    'list' => [
                        $val['color_id'] => [
                            'color_id' => $val['color_id'],
                            'color_name' => $val['color_name'],
                            'inputQuantity' => $val['goods_number'],
                            'stockQuantity' => $stockQuantity,
                            'sizeList' => [
                                [
                                    'size_id' => $val['size_id'],
                                    'size_name' => $val['size_name'],
                                    'inputQuantity' => $val['goods_number'],
                                    'stockQuantity' => $stockQuantity1,
                                ]
                            ],
                        ]
                    ]
                ];
            }
        }
        // 去除标记的key
        foreach ($temp as $value) {
            $value['list'] = array_values($value['list']);
            $temp1[] = $value;
        }
        $arr['details'] = $temp1;
        foreach ($arr['details'] as &$item) {
            $item['goods_pprice'] = $item['goods_price'];
        }
        if (!$res) {
            return $this->renderError('查询失败');
        }
        // 查询附件信息
        $attachModel = new AttachModel();
        $attach = json_decode($attachModel->where('orders_id', '=', $arr['orders_id'])->where('order_type', '=', 1)->order('id', 'DESC')->find(), true);
        if ($attach) {
            $attach['downlod_url'] = "http://" . $attach['url'];
        }
        $arr['attach'] = $attach;
        return $this->renderSuccess($arr, '查询成功');
    }


    /**
     * 函数描述:删除订单+订单详情
     * @param Request $request
     * Date: 2021/1/11
     * Time: 16:27
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author mll
     */
    public function delPurchaseOrders(Request $request)
    {
        $data = $request->get();
        $purchaseModel = new PurchaseOrdersModel();
        $purchaseValidate = new PurchaseOrdersValidate();
        if (!$purchaseValidate->scene('del')->check($data)) {
            return $this->renderError($purchaseValidate->getError());
        }
        $lock_where = [
            'com_id' => $this->com_id,
            'orders_id' => $data['orders_id'],
            'orders_code' => $data['orders_code']
        ];
        $lock = $purchaseModel->where($lock_where)->find();
        if (!$lock['lock_version'] == $data['lock_version']) {
            return $this->renderError('版本不正确，不可以删除');
        }
        if ($lock['orders_status'] == 9) {
            return $this->renderError('正式单据不可删除');
        }
        $purchaseOrdersDetailsModel = new PurchaseOrdersDetailsModel();
        Db::startTrans();
        //先查询 details表 是否有对应的数据
        $res_details = $purchaseOrdersDetailsModel->where($lock_where)->select()->toArray();
        if ($res_details) {
            $del_detail = $purchaseOrdersDetailsModel->where($lock_where)->delete();
            if (!$del_detail) {
                Db::rollback();
                return $this->renderError('删除采购单详情失败');
            }
        }
        //如果details为空，直接删除订单表
        $del_order = $purchaseModel->where($lock_where)->delete();
        if (!$del_order) {
            Db::rollback();
            return $this->renderError('删除采购单失败');
        }
        Db::commit();
        return $this->renderSuccess('', '删除采购单成功');
    }

    /**
     * 函数描述:采购单编辑
     * @param Request $request
     * Date: 2021/1/12
     * Time: 9:38
     * @author mll
     */
    public function editPurchaseOrders(Request $request)
    {
        $data = $request->post();
        $purchaseModel = new PurchaseOrdersModel();
        $purchaseValidate = new PurchaseOrdersValidate();
        if (!$purchaseValidate->scene('edit')->check($data)) {
            return $this->renderError($purchaseValidate->getError());
        }
        $res = $purchaseModel->editDetail($data);
    }

    /**
     * 函数描述: 进货分析
     * @param Request $request
     * Date: 2021/3/30
     * Time: 10:05
     * @author mll
     */
    public function purchaseAnalysis(Request $request)
    {
        /**
         *  type:
         *      0 为自定义  接收 start_time ，end_time
         *      1 昨日     $start_time = strtotime(date("Y-m-d",strtotime("-1 day")));
         * $end_time = strtotime(date("Y-m-d",strtotime("-1 day")))+86399;
         *      2 今日     $start_time = strtotime(date("Y-m-d",time()));
         * $end_time = strtotime(date("Y-m-d",time()))+86399;
         *      3 本周    $start_time = strtotime(date('Y-m-d', strtotime("this week Monday", time()))),
         * $end_time = strtotime(date('Y-m-d', strtotime("this week Sunday", time()))) + 24 * 3600 - 1
         *      4 本月    mktime(0, 0, 0, date('m'), 1, date('Y')),
         * mktime(23, 59, 59, date('m'), date('t'), date('Y'))
         *      5 本季     $season = ceil((date('n'))/3) //当月是第几季节
         * date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))),"\n";
         * date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))),"\n";
         *      6 本年    mktime(0, 0, 0, 1, 1, date('Y')),
         * mktime(23, 59, 59, 12, 31, date('Y'))
         */
        $data = $request->get();
        $time = $data['time'];
        switch ($data['type']) {
            case 0: //全部时间
                return $this->analysisApi("","",$time);
                break;
            case 1: //昨日
                $start_time = strtotime(date("Y-m-d",strtotime("-1 day")));
                $end_time = strtotime(date("Y-m-d",strtotime("-1 day")))+86399;
                return $this->analysisApi($start_time,$end_time,$time);
                break;
            case 2://今日
                $start_time = strtotime(date("Y-m-d",time()));
                $end_time = strtotime(date("Y-m-d",time()))+86399;
                return $this->analysisApi($start_time,$end_time,$time);
                break;
            case 3://本周
                $start_time = strtotime(date('Y-m-d', strtotime("this week Monday", time())));
                $end_time = strtotime(date('Y-m-d', strtotime("this week Sunday", time()))) + 24 * 3600 - 1;
                return $this->analysisApi($start_time,$end_time,$time);
                break;
            case 4://本月
                $start_time = mktime(0, 0, 0, date('m'), 1, date('Y'));
                $end_time = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
                return $this->analysisApi($start_time,$end_time,$time);
                break;
            case 5://本季
                $season = ceil((date('n'))/3); //当月是第几季节
                $start_time = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
                $end_time = strtotime(date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0, 0,$season*3,1,date("Y"))),date('Y'))));
                return $this->analysisApi($start_time,$end_time,$time);
                break;
            case 6: //本年
                $start_time = mktime(0, 0, 0, 1, 1, date('Y'));
                $end_time = mktime(23, 59, 59, 12, 31, date('Y'));
                return $this->analysisApi($start_time,$end_time,$time);
                break;
            case 7://自定义
                $start_time = strtotime($data['start_time']);
                $end_time = strtotime($data['end_time']);
                return $this->analysisApi($start_time,$end_time,$time);
                break;
        }
    }

    public function analysisApi($start_time,$end_time,$time)
    {

        $purchaseModel = new PurchaseOrdersModel();
//        if ($start_time != "" && $end_time != ""){
        if (isset($start_time) && isset($end_time)){
            $where = [
                'start_time' => $start_time,
                'end_time' => $end_time,
                'orders_status' => 9
            ];
        }
        if ($this->org_ids) {
            $whereIn = [
                ['warehouse_id', 'in', $this->org_ids]
            ];
        } else {
            $whereIn = [
                ['com_id', '=', $this->com_id]
            ];
        }
        switch ($time){
            case "0" : //按天分组
                $where['time'] = '%Y-%m-%d';
                $res = $purchaseModel->getPurchaseAnalysis($where, $whereIn);
                return $this->renderSuccess($res);
                break;
            case "1": //按周分组
                $where['time'] = '%X-%V';
                $res = $purchaseModel->getPurchaseAnalysis($where, $whereIn);
                foreach ($res as &$v) {
                    $v['date'] = str_replace("-","第",$v['date']).'周';
                }
                return $this->renderSuccess($res);
                break;
            case "2": //按月分组
                $where['time'] = '%Y-%m';
                $res = $purchaseModel->getPurchaseAnalysis($where, $whereIn);
                return $this->renderSuccess($res);
                break;
            case "3": //按季节分组
                $where['time'] = 'quarter';
                $res = $purchaseModel->getPurchaseAnalysis($where, $whereIn);
                return $this->renderSuccess($res);
                break;
            case "4": //按年分组
                $where['time'] = '%Y';
                $res = $purchaseModel->getPurchaseAnalysis($where, $whereIn);
                return $this->renderSuccess($res);
                break;
        }
    }
}