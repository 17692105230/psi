<?php

namespace app\web\controller;


use app\common\utils\COS;
use app\web\model\GoodsDetails as GoodsDetailsModel;
use app\Request;
use app\web\model\Goods as GoodModel;
use app\web\model\GoodsAssist as GoodsAssistModel;
use app\web\model\PurchaseOrdersDetails;
use app\web\validate\Goods as GoodsValidate;
use think\facade\Db;
use app\web\model\Color as ColorModel;
use think\Validate;
use app\web\model\GoodsStock as GoodsStockModel;

/**
 * 商品
 * Class Goods
 * @package app\web\controller
 */
class Goods extends Controller
{
    /**
     * @var ColorModel
     */
    protected $goodsModel;
    protected $goodsValidate;

//    初始化
    public function initialize()
    {
        parent::initialize();
        $this->goodsModel = new GoodModel();
        $this->goodsValidate = new GoodsValidate();
    }

    /**
     * 函数描述:小程序采购单列表商品公共类
     * @param $res
     * @param $org_id
     * @return mixed
     * Date: 2021/1/16
     * Time: 15:20
     * @author mll
     */
    public function colorAndSizeList($res, $org_id)
    {
        $stockModel = new GoodsStockModel();
        // 获取goods_id
        $goods_ids = array_column(json_decode($res, true), 'goods_id');
        // 查询库存
        $where = [['goods_id', 'in', $goods_ids]];
        if ($org_id) {
            array_push($where, ['warehouse_id', '=', $org_id]);
        }
        $stock_list = json_decode($stockModel->where($where)->select(), true);
        //如果查询成功循环当前数组
        foreach ($res as $item) {
            $goods_id = $item['goods_id'];
            $item['stockQuantity'] = array_sum(array_filter(array_map(function ($stock) use ($goods_id) {
                if ($stock['goods_id'] == $goods_id) {
                    return $stock['stock_number'];
                }
            }, $stock_list)));
            $item['inputQuantity'] = 0;
            $result = [];
            //循环数组下的detail，拿出尺寸和颜色
            foreach ($item['detail'] as $value) {
                $size_id = $value['size_id'];
                $color_id = $value['color_id'];
                $size_number = array_sum(array_filter(array_map(function ($stock) use ($goods_id, $size_id, $color_id) {
                    if ($stock['goods_id'] == $goods_id && $size_id == $stock['size_id'] && $color_id == $stock['color_id']) {
                        return $stock['stock_number'];
                    }
                }, $stock_list)));
                if (isset($result[$color_id])) {
                    // 如果颜色存在
                    $result[$color_id]['sizeList'][] = [
                        'size_id' => $value['size_id'],
                        'size_name' => $value['size_name'],
                        'inputQuantity' => 0,
                        'stockQuantity' => $size_number,//库存量
                    ];
                } else {
                    $color_number = array_sum(array_filter(array_map(function ($stock) use ($goods_id, $color_id) {
                        if ($stock['goods_id'] == $goods_id && $color_id == $stock['color_id']) {
                            return $stock['stock_number'];
                        }
                    }, $stock_list)));
                    $result[$color_id] = [
                        'color_id' => $value['color_id'],
                        'color_name' => $value['color_name'],
                        'inputQuantity' => 0,
                        'stockQuantity' => $color_number,//库存量
                    ];
                    $result[$color_id]['sizeList'][] = [
                        'size_id' => $value['size_id'],
                        'size_name' => $value['size_name'],
                        'inputQuantity' => 0,
                        'stockQuantity' => $size_number,//库存量
                    ];
                }
            }
            $item['list'] = array_values($result);
        }
        return $res;
    }

    /**
     * 函数描述:小程序商品列表
     * Date: 2021/1/5
     * Time: 11:46
     * @author mll
     */
    public function loadGoodsWX(Request $request)
    {
        $params = $request->get();
        $goods_model = new GoodModel();
        $where = [
            ['com_id', '=', $this->com_id]
        ];
        $category = isset($params['category']) ? $params['category'] : [];
        if ($category) {
            array_push($where, ['category_id', 'in', $category]);
        }
        $goods_status = isset($params['goods_status']) ? $params['goods_status'] : 0; // 上架状态(-1 不显示 0全部 1显示)
        if ($goods_status) {
            if ($goods_status == -1) {
                // 不显示
                array_push($where, ['goods_status', '=', 0]);
            } else if ($goods_status == 1) {
                // 显示
                array_push($where, ['goods_status', '=', 1]);
            }
        }
        $search = isset($params['search']) ? $params['search'] : '';
        $whereOr = [];
        if ($search) {
            array_push($whereOr, ['goods_code', 'like', "%{$search}%"]); // 商品货号
            array_push($whereOr, ['goods_serial', 'like', "%{$search}%"]); // 商品序号
            array_push($whereOr, ['goods_barcode', 'like', "%{$search}%"]); // 商品条码
            array_push($whereOr, ['goods_name', 'like', "%{$search}%"]); // 商品名称
        }
        $res = $goods_model->getGoodsWX($where, $whereOr, 10);
        if ($res) {
            return $this->renderSuccess($res, '查询成功');
        }
        return $this->renderError('服务器异常');
    }

    /**
     * 函数描述:采购单商品列表list
     * @return array
     * Date: 2021/1/15
     * Time: 16:13
     * @author mll
     */
    public function loadPurchaseGoods(Request $request)
    {
        $data = $request->get();
        $data['com_id'] = $this->com_id;
        $org_id = isset($data['org_id']) ? $data['org_id'] : '';
        unset($data['org_id']);
        $res = $this->goodsModel->puchaseOrdersList($data);
        if ($res) {
            $res1 = $this->colorAndSizeList($res, $org_id);
            return $this->renderSuccess($res1, '查询成功');
        }
        return $this->renderError('查询失败');
    }

    /**
     * 盘点单商品列表 适用于 盘点单商品列表 商品颜色尺码不同就是多个商品
     * zll
     * Date: 2021/5/26
     * Time: 10:08
     * @param Request $request
     * @return array
     */
    public function loadInventoryGoods(Request $request)
    {
        $data = $request->get();
        $data['com_id'] = $this->com_id;
        $res = $this->goodsModel->orderListCell($data);

        return $this->renderSuccess($res);
    }

    /**
     * 函数描述:获取商品详情
     * @param Request $request
     * Date: 2021/1/6
     * Time: 16:22
     * @author mll
     */
    public function getGoodsDetail(Request $request)
    {
        $goods_id = $request->get('goods_id');
        if (empty($goods_id)) {
            return $this->renderError('goods_id不能为空');
        }
        $goodsResult = $this->goodsModel->getGoodsWX(['goods_id' => $goods_id], []);
        if (!$goodsResult) {
            return $this->renderError('服务器内部出错');
        }
        return $this->renderSuccess($goodsResult, '查询成功');
    }

    /**
     * 函数描述:小程序添加商品
     * @param Request $request
     * Date: 2021/1/5
     * Time: 16:58
     * @author mll
     */
    public function saveGoods(Request $request)
    {
        $sum_data = $request->post();
        if (!$this->goodsValidate->scene('add')->check($sum_data)) {
            return $this->renderError($this->goodsValidate->getError());
        }
        //查询goods_code是否重复
        $gc = $this->goodsModel->where(['goods_code' => $sum_data['goods_code'], 'com_id' => $this->com_id])->find();
        if ($gc) {
            //为真代表goods_code已经存在
            return $this->renderError('商品编号重复');
        }

        $data = [
            'goods_code' => $sum_data['goods_code'],
            'goods_name' => $sum_data['goods_name'],
            'category_id' => $sum_data['category_id'],
            'goods_pprice' => $sum_data['goods_pprice'],
            'goods_rprice' => $sum_data['goods_rprice'],
//            'goods_wprice' => $sum_data['goods_wprice'],
//            'goods_srprice' => $sum_data['goods_srprice'],
            'goods_status' => $sum_data['goods_status'],
            'integral' => $sum_data['integral'],
            'com_id' => $this->com_id
        ];
        if (isset($sum_data['goods_barcode']) && $sum_data['goods_barcode']) {
            $goods_barcode_exist = $this->goodsModel->where(['goods_barcode' => $sum_data['goods_barcode'], 'com_id' => $this->com_id])->find();
            if ($goods_barcode_exist) {
                return $this->renderError("商品条码重复");
            }
            $data['goods_barcode'] = $sum_data['goods_barcode'];
        }
        Db::startTrans();
        $res = $this->goodsModel->save($data);
        if (!$res) {
            Db::rollback();
            return $this->renderError('添加商品失败');
        }
        $goods_id = $this->goodsModel->goods_id;
        $goods_details = new GoodsDetailsModel();
        $color_ids = $sum_data['color_id'];
        $size_ids = $sum_data['size_id'];
        $ids[] = ['color_id' => $color_ids, 'size_id' => $size_ids];
        $color_ids = explode(',', $color_ids);
        $size_ids = explode(',', $size_ids);
        $temp = [];
        foreach ($color_ids as $color) {
            foreach ($size_ids as $size) {
                $temp = ['color' => $color, 'size' => $size];
                $item = [
                    'goods_code' => $sum_data['goods_code'],
                    'goods_id' => $goods_id,
                    'color_id' => $temp['color'],
                    'size_id' => $temp['size'],
                    'lock_version' => 0,
                    'com_id' => $this->com_id,
                    'create_time' => time(),
                    'update_time' => time(),
                ];
                $res2 = $goods_details->insert($item);
                if (!$res2) {
                    Db::rollback();
                    return $this->renderError('插入单品规格失败');
                }
            }
        }
        if (!empty($sum_data['images'])) {
            //图片保存
            $images = json_decode($sum_data['images'], true);
            $goods_assist = new GoodsAssistModel();
            foreach ($images as $key => $v) {
                $img = [
                    'goods_id' => $goods_id,
                    'assist_url' => $v['assist_url'],
                    'assist_extension' => $v['extension'],
                    'assist_sha1' => $v['key'],
                    'assist_md5' => $v['key'],
                    'assist_name' => $v['key'],
                    'assist_category' => 'image',
                    'com_id' => $this->com_id,
                    'assist_sort' => $v['sort'],
                    'assist_status' => 1,
                ];
                $img_res = $goods_assist->insert($img);
                if (!$img_res) {
                    Db::rollback();
                    return $this->renderError('第' . ($key + 1) . '图片上传出错');
                }
            }
        }
        Db::commit();
        return $this->renderSuccess('', '保存商品成功');
    }

    /**
     * 函数描述:修改商品详情
     * @param Request $request
     * @return array
     * Date: 2021/1/6
     * Time: 18:35
     * @author mll
     */
    public function updateGoodsDetails(Request $request)
    {
        /**
         * 修改之前先删除所有商品
         */

        $sum_data = $request->post();
        $goods_id = $sum_data['goods_id'];
        if (!$this->goodsValidate->scene('edit')->check($sum_data)) {
            return $this->renderError($this->goodsValidate->getError());
        }
        $lock = $this->goodsModel->findGoods(['goods_id' => $goods_id], 'lock_version');
        if (!$lock['lock_version'] == $sum_data['lock_version']) {
            return $this->renderError('版本错误');
        }
        $details_table = new GoodsDetailsModel();
        $assiest_table = new GoodsAssistModel();
        /**
         * 更改商品
         */
        $goods_data = [
            'goods_code' => $sum_data['goods_code'],
            'goods_name' => $sum_data['goods_name'],
            'category_id' => $sum_data['category_id'],
            'goods_pprice' => $sum_data['goods_pprice'],
            'goods_rprice' => $sum_data['goods_rprice'],
            'goods_status' => $sum_data['goods_status'],
            'goods_wprice' => $sum_data['goods_wprice'],
            'goods_srprice' => $sum_data['goods_srprice'],
            'integral' => $sum_data['integral'],
            'com_id' => $this->com_id,
        ];
        $goods_barcode_exist = $this->goodsModel->where([
            'goods_barcode' => $sum_data['goods_barcode'],
            'com_id' => $this->com_id,
        ])->find();
        if ($goods_barcode_exist && $goods_barcode_exist['goods_id'] != $goods_id) {
            return $this->renderError("商品条码重复");
        }
        $goods_data['goods_barcode'] = $sum_data['goods_barcode'];
        Db::startTrans();
        $res = $this->goodsModel->updateGoods($goods_data, ['goods_id' => $goods_id, 'com_id' => $this->com_id]);
        if (!$res) {
            Db::rollback();
            return $this->renderError('修改商品失败');
        }
        //判断有没有需要删除的图片，如果有根据图片id删除
        //json转数组
        $del_image_json = json_decode($sum_data['del_images'], true);
        if ($del_image_json) {
            // 删除图片记录
            $assist_ids = array_column($del_image_json, 'assist_id');
            $res = $assiest_table->whereIn('assist_id', $assist_ids)->delete();
            if (!$res) {
                Db::rollback();
                return $this->renderError("修改图片失败");
            }
            // 删除cos图片
            $md5s = array_column($del_image_json, 'assist_md5');
            $keys = array_map(function ($key) {
                return ['Key' => "{$key}"];
            }, $md5s);
            try {
                $cos = new COS();
                $data = $cos->delete_objects($keys);
                if (empty($data)) {
                    Db::rollback();
                    return $this->renderError("修改图片失败");
                }
            } catch (\Exception $e) {
                return $this->renderError($e->getMessage());
            }
        }
        /**
         * 添加商品详情
         */
        $color_ids = $sum_data['color_id'];
        $size_ids = $sum_data['size_id'];
        $ids[] = ['color_id' => $color_ids, 'size_id' => $size_ids];
        $color_ids = explode(',', $color_ids);
        $size_ids = explode(',', $size_ids);
        // 查询商品的所有颜色和尺码
        $details = json_decode($details_table->getList(['goods_id' => $goods_id, 'com_id' => $this->com_id]), true);
        $details_size = array_unique(array_column($details, "size_id"));
        $details_color = array_unique(array_column($details, 'color_id'));
        // 传入的尺寸和颜色不存在
        $new_size = array_diff($size_ids, $details_size);
        $new_color = array_diff($color_ids, $details_color);
        //合并现有和即将有的颜色尺码id
        $after_size = array_unique(array_merge($size_ids, $details_size));
        $after_color = array_unique(array_merge($color_ids, $details_color));
        $insert_rows = [];
        foreach ($after_color as $color) {
            foreach ($after_size as $size) {
                if (!in_array($color, $details_color) || !in_array($size, $details_size)) {
                    $item = [
                        'goods_code' => $sum_data['goods_code'],
                        'goods_id' => $goods_id,
                        'color_id' => $color,
                        'size_id' => $size,
                        'lock_version' => 0,
                        'com_id' => $this->com_id,
                        'create_time' => time(),
                        'update_time' => time(),
                    ];
                    array_push($insert_rows, $item);
                }
            }
        }
        if ($insert_rows) {
            $res2 = $details_table->insertAll($insert_rows);
            if (!$res2) {
                Db::rollback();
                return $this->renderError('插入单品规格失败');
            }
        }

        /**
         * 插入图片
         */
        $images_json = json_decode($sum_data['images'], true);
        if (!empty($images_json)) {
            //图片保存
            $rows = [];
            foreach ($images_json as $key => $v) {
                $img = [
                    'goods_id' => $goods_id,
                    'assist_url' => $v['assist_url'],
                    'assist_extension' => $v['extension'],
                    'assist_sha1' => $v['key'],
                    'assist_md5' => $v['key'],
                    'assist_name' => $v['name'],
                    'assist_category' => 'image',
                    'com_id' => $this->com_id,
                    'assist_sort' => $v['sort'],
                    'assist_status' => 1,
                ];
                array_push($rows, $img);
            }
            $img_res = $assiest_table->insertAll($rows);
            if (!$img_res) {
                Db::rollback();
                return $this->renderError("图片上传失败");
            }
        }
        Db::commit();
        return $this->renderSuccess('', '商品修改成功');
    }

    /**
     * 函数描述:模糊查询
     * @param Request $request
     * Date: 2021/1/20
     * Time: 15:54
     * @author mll
     */
    public function likeQuery(Request $request)
    {
        $data = $request->get();
        $where = array_merge($data, ['com_id' => $this->com_id]);
        $res = $this->goodsModel->likeQuery($where);
        $org_id = isset($data['org_id']) ? $data['org_id'] : '';
        if (!$res->isEmpty()) {
            $wxList = $this->colorAndSizeList($res, $org_id);
            if ($wxList) {
                return $this->renderSuccess($res, '查询成功');
            }
            return $this->renderError('查询失败');
        }
        return $this->renderError($res, '查询失败');
    }

}