<?php


namespace app\web\model;

use app\common\model\web\StockDiary as StockDiaryModel;
class StockDiary extends StockDiaryModel
{
    public function store($data)
    {
        return $this->save([
            'warehouse_id' => $data['warehouse_id'],
            'orders_code' => $data['orders_code'],
            'goods_code' => $data['goods_code'],
            'color_id' => $data['color_id'],
            'size_id' => $data['size_id'],
            'goods_id' => $data['goods_id'],
            'goods_number' => $data['goods_number'],
            'stock_number' => $data['stock_number'],
            'orders_type' => $data['orders_type'],
            'com_id' => $data['com_id'],
            'shop_id' => isset($data['shop_id']) ? $data['shop_id'] : 0,
        ]);

    }
    public function getDiary()
    {
        $data = $this->alias('sd')
            ->join('hr_goods g', 'g.goods_code=sd.goods_code', 'left')
            ->join('hr_size s', 'sd.size_id = s.size_id', 'left')
            ->join('hr_color c', 'sd.color_id = c.color_id', 'left')
            ->field('sd.*,g.goods_name,g.goods_barcode,s.size_name,c.color_name')
            ->select();
        return $data;
    }
    //查询库存
    public function searchDiarys($where,$rows=10)
    {
        $data = $this->alias("sd")
            ->leftJoin('hr_organization org', 'sd.warehouse_id=org.org_id')
            ->leftJoin('hr_goods goods', 'sd.goods_id=goods.goods_id')
            ->leftJoin('hr_color color', 'sd.color_id=color.color_id')
            ->leftJoin('hr_size size', 'sd.size_id=size.size_id')
            ->where("sd.warehouse_id", $where["warehouse_id"])
            ->where("sd.com_id", $where["com_id"])
            ->where("sd.goods_code", $where["search_keyword"])
            ->when(!empty($where["color_id"]), function($query)use($where){
                $query->where("sd.color_id", $where["color_id"]);
            })
            ->when(!empty($where['size_id']), function ($query)use($where) {
                $query->where("sd.size_id", $where["size_id"]);
            })
            ->when(!empty($where['begin_date']) || !empty($where["end_date"]), function ($query)use($where){
                $begin_date = $where["begin_date"] ? strtotime($where["begin_date"]) : strtotime("1999-09-09");
                $end_date = $where["end_date"] ? strtotime($where["end_date"]) : time();
                $query->whereBetween('sd.create_time', [$begin_date, $end_date]);
            })
            ->paginate($rows);
        return $data;
    }

}