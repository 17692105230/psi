<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/5
 * Time: 14:18
 */

namespace app\common\model\web;


use app\common\model\BaseModel;

class GoodsDetails extends BaseModel
{
    protected $table = "hr_goods_details";
    protected $pk = "details_id";
}