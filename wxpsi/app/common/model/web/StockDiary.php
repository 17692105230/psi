<?php


namespace app\common\model\web;


use app\common\model\BaseModel;

class StockDiary extends BaseModel
{
    protected $table = 'hr_stock_diary';
    protected $pk = 'details_id';

    //添加仓库更新日志
    public static function addStockDiary($data){
        return (new StockDiary())->saveAll($data);
    }
}