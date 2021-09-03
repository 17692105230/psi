<?php

namespace app\web\controller;

use app\web\model\GoodsAssist as GoodsAssistModel;
use app\web\model\GoodsStock as GoodsStockModel;
use Symfony\Component\VarDumper\Command\Descriptor\DumpDescriptorInterface;
use think\App;
use think\Exception;
use think\facade\Log;
use think\facade\View;
use app\common\service\Render;
use think\exception\ValidateException;
use app\web\model\WxUser as WxUserModel;
use app\common\exception\BaseException;
use think\cache\driver\Redis;
use app\web\model\User as userModel;

class Controller extends \app\BaseController
{

    use Render;

    // 企业id
    protected $com_id = 0; // 企业id
    protected $user = null; // 用户信息
    protected $user_id = 0; // 用户id
    protected $org_ids = null; // 组织机构id

    /**
     * 初始化
     */
    public function initialize()
    {
        $this->get_user();
        $log = '>>> request: /' . $this->request->url();
        $log .= ' ┃ user_id: ' . intval($this->user_id);
        $log .= ' ┃ params: ' . json_encode($this->request->param(), JSON_UNESCAPED_UNICODE);
        Log::info($log);
    }

    /**
     * 获取用户信息
     * @return mixed
     */
    protected function get_user()
    {
        $token = $this->request->param('token');
        $userModel = new userModel();
        $user = $userModel->get_user_by_token($token);
        if ($user) {
            // 用户存在
            $this->user = $user;
            $this->user_id = $user['user_id'];
            $this->com_id = $user['com_id'];
            if ($user['org_id']) {
                $this->org_ids = array_filter(explode(',', $user['org_id']));
            }
        }
    }

    protected function throwError($msg, $code = 0)
    {
        throw new BaseException(['errcode' => $code, 'msg' => $msg]);
    }

    protected function getData($data = [])
    {
        return array_merge($data, ['com_id' => $this->com_id]);
    }

    /**
     * 处理商品分类结构
     * @param $data
     * @param $colorData
     * @param $sizeData
     * @param $goodsData
     * @return array
     */
    public function _dealDetailsData($data, $colorData, $sizeData, $goodsData)
    {
        $result = [];
        $goodsAssistModel = new GoodsAssistModel();
        // 查询库存
        $goods_ids = array_column($data, 'goods_id');
        $stockModel = new GoodsStockModel();
        $stock_list = json_decode($stockModel->getListByWhereIn('goods_id', $goods_ids), true);
        foreach ($data as $v) {
            $color_id = $v['color_id'];
            $size_id = $v['size_id'];
            $goods_id = $v['goods_id'];
            $img = json_decode($goodsAssistModel->where('goods_id', '=', $v['goods_id'])->order('assist_sort ASC')->select(), true);
            $images = array_map(function ($v) {
                $v['assist_url'] = "http://" . $v['assist_url'];
                return $v;
            }, $img);
            // 总库存
            $total_stockQuantity = array_sum(array_filter(array_map(function ($stock) use ($goods_id) {
                if ($stock['goods_id'] == $goods_id) {
                    return $stock['stock_number'];
                }
            }, $stock_list)));
            // 颜色一样的库存
            $stockQuantity = array_sum(array_filter(array_map(function ($stock) use ($goods_id, $color_id) {
                if ($stock['goods_id'] == $goods_id && $color_id == $stock['color_id']) {
                    return $stock['stock_number'];
                }
            }, $stock_list)));
            // 尺寸颜色都一样的库存
            $stockQuantity1 = array_sum(array_filter(array_map(function ($stock) use ($goods_id, $color_id, $size_id) {
                if ($stock['goods_id'] == $goods_id && $color_id == $stock['color_id'] && $size_id == $stock['size_id']) {
                    return $stock['stock_number'];
                }
            }, $stock_list)));
            if (isset($result[$v['goods_id']])) {
                // 存在商品
                $goods = $result[$v['goods_id']];
                if (isset($goods['list'][$v['color_id']])) {
                    // 存在颜色
                    $sizeList = $goods['list'][$v['color_id']]['sizeList'];
                    if (isset($sizeList[$v['size_id']])) {
                        // 存在尺寸
                        $result[$v['goods_id']]['list'][$v['color_id']]['sizeList'][$v['size_id']]['inputQuantity'] += $v['goods_number'];
                    } else {
                        // 未存在尺寸
                        $result[$v['goods_id']]['list'][$v['color_id']]['sizeList'][$v['size_id']] = [
                            'size_id' => $v['size_id'],
                            'size_name' => $sizeData[$v['size_id']]['size_name'],
                            'inputQuantity' => $v['goods_number'],
                            'stockQuantity' => $stockQuantity1,
                        ];
                    }
                    $result[$v['goods_id']]['list'][$v['color_id']]['inputQuantity'] += $v['goods_number'];
                } else {
                    // 未存在颜色
                    $result[$v['goods_id']]['list'][$v['color_id']] = [
                        'color_id' => $v['color_id'],
                        'color_name' => $colorData[$v['color_id']]['color_name'],
                        'inputQuantity' => $v['goods_number'],
                        'stockQuantity' => $stockQuantity,
                        'sizeList' => [
                            $v['size_id'] => [
                                'size_id' => $v['size_id'],
                                'size_name' => $sizeData[$v['size_id']]['size_name'],
                                'inputQuantity' => $v['goods_number'],
                                'stockQuantity' => $stockQuantity1,
                            ]
                        ]
                    ];
                }
                $result[$v['goods_id']]['inputQuantity'] += $v['goods_number'];
            } else {
                // 未存在商品
                $result[$v['goods_id']] = [
                    'goods_code' => $v['goods_code'],
                    'goods_id' => $v['goods_id'],
                    'goods_price' => $v['goods_price'],
                    'goods_pprice' => $v['goods_price'],
                    'goods_rprice' => $v['goods_price'],
                    'goods_tmoney' => $v['goods_tmoney'],
                    'inputQuantity' => $v['goods_number'],
                    'goods_status' => $v['goods_status'],
                    'stockQuantity' => $total_stockQuantity,
                    'images' => $images,
                    'list' => [
                        $v['color_id'] => [
                            'color_id' => $v['color_id'],
                            'color_name' => $colorData[$v['color_id']]['color_name'],
                            'inputQuantity' => $v['goods_number'],
                            'stockQuantity' => $stockQuantity,
                            'sizeList' => [
                                $v['size_id'] => [
                                    'size_id' => $v['size_id'],
                                    'size_name' => $sizeData[$v['size_id']]['size_name'],
                                    'inputQuantity' => $v['goods_number'],
                                    'stockQuantity' => $stockQuantity1,
                                ]
                            ]
                        ]
                    ],
                ];
            }
        }
        // 去除key
        $result = array_values($result);
        $result = array_map(function ($v) use ($goodsData) {
            $list = array_map(function ($a) {
                $a['sizeList'] = array_values($a['sizeList']);
                return $a;
            }, $v['list']);
            $v['list'] = array_values($list);
            $v['goods_name'] = $goodsData[$v['goods_id']]['goods_name'];
            return $v;
        }, $result);
        return $result;
    }

    //查询是否有基础数据
    public function checkNullData()
    {
        return $this->renderSuccess([], "1");
        //查询商品
        if (!(new \app\web\model\Goods())
            ->where("com_id", "=", $this->com_id)
            ->where("goods_status", "=", 1)
            ->count()) {
            return $this->renderSuccess([], "请先添加商品");
        }
        //查询仓库
        if (!(new \app\web\model\Organization())
            ->where("com_id", "=", $this->com_id)
            ->where("org_status", "in", "1,2")
            ->count()) {
            return $this->renderSuccess([], "请先添加店仓");
        }
        //查询客户
        if (!(new \app\web\model\ClientBase())
            ->where("com_id", "=", $this->com_id)
            ->where("client_status", "=", 1)
            ->count()) {
            return $this->renderSuccess([], "请先添加客户");
        }
        //查询用户
        if (!(new \app\web\model\User())
            ->where("com_id", "=", $this->com_id)
            ->where("user_status", "=", 1)
            ->count()) {
            return $this->renderSuccess([], "请先添加销售员");
        }
        //查询供应商
        if (!(new \app\web\model\Supplier())
            ->where("com_id", "=", $this->com_id)
            ->where("supplier_status", "=", 1)
            ->count()) {
            return $this->renderSuccess([], "请先添加供应商");
        }
        //查询其他费用
        if (!(new \app\web\model\Dict())
            ->where("com_id", "=", 0)
            ->where("dict_status", "=", 1)
            ->where("dict_type", "=", 'account')
            ->count()) {
            return $this->renderSuccess([], "请先添加其他费用");
        }
        //查询结算账户
        if (!(new \app\web\model\Settlement())
            ->where("com_id", "=", $this->com_id)
            ->where("settlement_status", "=", 1)
            ->count()) {
            return $this->renderSuccess([], "请先添加结算账户");
        }
        return $this->renderSuccess([], "1");
    }
}
