<?php


namespace app\web\model;

use app\common\model\web\GoodsStock as GoodsStockModel;
use app\web\model\StockDiary as StockDiaryModel;
use think\Exception;

class GoodsStock extends GoodsStockModel
{
    /**
     * 查询库存数量
     */
    public function getStockValue($where, $valueField)
    {
        return $this->where($where)->value($valueField);
    }


    /**
     * @desc  添加或者更新库存
     * @param $data
     * @param $order_type
     * @return bool
     * Date: 2021/1/8
     * Time: 18:19
     * @author myy
     */
    public function updateOrInsert($data, $orders_type = '采购单')
    {
        $where = [
            'warehouse_id' => $data['warehouse_id'],
            'goods_code' => $data['goods_code'],
            'goods_id' => $data['goods_id'],
            'color_id' => $data['color_id'],
            'size_id' => $data['size_id'],
        ];
        $info = $this->getOne($where);

        $stockDiaryModel = new StockDiaryModel();
        if ($info) {
            if($orders_type == '采购退货单'){
                if($info['stock_number'] < 0 && $info['stock_number'] == 0){
                    throw new Exception('库存不足');
                }
            }
            $info->save([
                'last_orders_code' => $data['orders_code'],
                'stock_number' => $info['stock_number'] + $data['stock_number']
            ]);

            // 写入库存流水
            $data['stock_number'] = $info['stock_number'];
            $data['orders_type'] = $orders_type;
            $res1 = $stockDiaryModel->store($data);
            if (!$res1) {
                throw new Exception('写入库存流失失败');
            }
            return $res1;
        }
        $saveInfo = $this->save(array_merge(
            $where, [
                'com_id' => $data['com_id'],
                'stock_number' => $data['stock_number'],
                'last_orders_code' => $data['orders_code'],
                'shop_id' => isset($data['shop_id']) ? $data['shop_id'] : 0,
            ]
        ));
        // 写入库存流水
        $data['stock_number'] = $saveInfo['stock_number'];
        $data['goods_number'] = $data['stock_number'];
        $data['orders_type'] = $orders_type;
        $res1 = $stockDiaryModel->store($data);
        if (!$res1) {
            throw new Exception('写入库存流失失败');
        }
        return $res1;

    }


    public function getGoodsList($where, $page, $rows)
    {
        $total = $this->alias('gs')
            ->join('hr_goods g', 'g.goods_id=gs.goods_id', 'left')
            ->where('gs.com_id', $where['com_id'])
            // 仓库id
            ->when(isset($where['warehouse_id']) && $where['warehouse_id'], function ($query) use ($where) {
                $query->where('gs.warehouse_id', $where['warehouse_id']);
            })
            // 商品名称,货号,条码
            ->when(isset($where['goods_ncb']) && $where['goods_ncb'], function ($query) use ($where) {
                $query->whereOr([["g.goods_name", "like", "%{$where['goods_ncb']}%"], ["g.goods_code", "like", "%{$where['goods_ncb']}%"], ["g.goods_barcode", "like", "%{$where['goods_ncb']}%"]]);
            })
            // 分类
            ->when(isset($where['category_id']) && $where['category_id'], function ($query) use ($where) {
                $query->where('g.category_id', $where['category_id']);
            })
            // 品牌
            ->when(isset($where['bland_id']) && $where['bland_id'], function ($query) use ($where) {
                $query->where('g.bland_id', $where['bland_id']);
            })
            //年份
            ->when(isset($where['goods_year']) && $where['goods_year'], function ($query) use ($where) {
                $query->where('g.goods_year', $where['goods_year']);
            })
            //季节
            ->when(isset($where['goods_season']) && $where['goods_season'], function ($query) use ($where) {
                $query->where('g.goods_season', $where['goods_season']);
            })
            //库存预警
            ->when(isset($where["goods_warn"]) && $where["goods_warn"] == 0, function ($query) {
                $query->where('gs.stock_number <= g.goods_llimit or gs.stock_number => g.goods_ulimit');
            })
            //库存0
            ->when(isset($where["goods_no"]) && $where["goods_no"] == 0, function ($query) {
                $query->where('gs.stock_number', "<=", 0);
            })
            ->count();
        $offset = ($page - 1) * $rows;
        $rows = $this->alias('gs')
            ->join('hr_goods g', 'g.goods_code=gs.goods_code', 'left')
            ->join('hr_goods_assist a', 'a.goods_id=g.goods_id', 'left')
            ->where('gs.com_id', $where['com_id'])
            // 仓库id
            ->when(isset($where['warehouse_id']) && $where['warehouse_id'], function ($query) use ($where) {
                $query->where('gs.warehouse_id', $where['warehouse_id']);
            })
            // 商品名称,货号,条码
            ->when(isset($where['goods_ncb']) && $where['goods_ncb'], function ($query) use ($where) {
                $query->whereOr([["g.goods_name", "like", "%{$where['goods_ncb']}%"], ["g.goods_code", "like", "%{$where['goods_ncb']}%"], ["g.goods_barcode", "like", "%{$where['goods_ncb']}%"]]);
            })
            // 分类
            ->when(isset($where['category_id']) && $where['category_id'], function ($query) use ($where) {
                $query->where('g.category_id', $where['category_id']);
            })
            // 品牌
            ->when(isset($where['bland_id']) && $where['bland_id'], function ($query) use ($where) {
                $query->where('g.bland_id', $where['bland_id']);
            })
            //年份
            ->when(isset($where['goods_year']) && $where['goods_year'], function ($query) use ($where) {
                $query->where('g.goods_year', $where['goods_year']);
            })
            //季节
            ->when(isset($where['goods_season']) && $where['goods_season'], function ($query) use ($where) {
                $query->where('g.goods_season', $where['goods_season']);
            })
            //库存预警
            ->when(isset($where["goods_warn"]) && $where["goods_warn"] == 0, function ($query) {
                $query->where('gs.stock_number <= g.goods_llimit or gs.stock_number => g.goods_ulimit');
            })
            //库存0
            ->when(isset($where["goods_no"]) && $where["goods_no"] == 0, function ($query) {
                $query->where('gs.stock_number', "<=", 0);
            })
            ->limit($offset, $rows)
            ->order('gs.create_time', 'desc')
            ->group('gs.goods_code,gs.warehouse_id')
            ->join('hr_organization o', 'o.org_id=gs.warehouse_id')
            ->join('hr_color c', 'c.color_id=gs.color_id')
            ->join('hr_size s', 's.size_id=gs.size_id')
            ->join('hr_category ca', 'ca.category_id=g.category_id')
            ->join('hr_dict d', 'd.dict_id=g.unit_id')
            ->field('a.assist_url,gs.warehouse_id,g.goods_name,o.org_name warehouse_name,c.color_name,s.size_name,ca.category_name,d.dict_name unit_name,g.goods_year,g.goods_barcode,g.goods_code,sum(gs.stock_number) total_stock_number')
            ->select();
        return compact('total', 'rows');
    }

    public function getGoodsStockDetail($goods_code, $warehouseId)
    {
        $data = $this->alias('gs')
            ->where(['gs.goods_code' => $goods_code, 'gs.warehouse_id' => $warehouseId])
            ->join('hr_color c', 'c.color_id = gs.color_id', 'left')
            ->join('hr_size s', 's.size_id = gs.size_id', 'left')
            ->field('gs.*,c.color_name,s.size_name')
            ->order('gs.color_id asc,gs.size_id asc')
            ->select();
        return $data;
    }

    public function getSizeColor($warehouse_id, $goods_code)
    {
        $data = $this->where('goods_code', $goods_code)
            ->where('warehouse_id', $warehouse_id)
            ->field('color_id,size_id')
            ->select()->toArray();

        $color = array_column($data, 'color_id');
        $size = array_column($data, 'size_id');
        return compact('color', 'size');
    }


}