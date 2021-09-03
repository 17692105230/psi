<?php


namespace app\common\model\web;

use app\common\model\BaseModel;


class Goods extends BaseModel
{
    protected $table = 'hr_goods';
    protected $pk = 'goods_id';

    //测试地址，上线修改，拼接图片地址

    public function images()
    {
        return $this->hasMany(GoodsAssist::class, 'goods_id', 'goods_id')
            ->where(['assist_category' => 'image', 'assist_status' => 1])
            ->withAttr('assist_url', function ($value) {
                return "http://" . $value;
            })
            ->order('assist_sort asc');
    }

    public function detail()
    {
        return $this->hasMany(GoodsDetails::class, 'goods_id', 'goods_id')
            ->alias('d')
            ->join('hr_color c', 'c.color_id = d.color_id', 'left')
            ->join('hr_size s', 's.size_id = d.size_id', 'left')
            ->field('d.*,c.color_name,s.size_name');
    }

    public function quantity()
    {
        return $this->hasMany(StockDiary::class, 'goods_code', 'goods_code')
            ->alias('d')
            ->join('hr_color c', 'c.color_id = d.color_id', 'left')
            ->join('hr_size s', 's.size_id = d.size_id', 'left')
            ->field('d.*');
    }

}