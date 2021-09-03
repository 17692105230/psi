<?php
/**
 * Created by PhpStorm.
 * User: zll
 * Date: 2021/1/13
 * Time: 18:01
 */

namespace app\common\model\web;


use app\common\model\BaseModel;

class WxUser extends BaseModel
{
    protected $table = "hr_wx_user";

    public static function detail($where)
    {
        $filter = ['is_delete' => 0];
        if (is_array($where)) {
            $filter = array_merge($filter, $where);
        } else {
            $filter['user_id'] = (int)$where;
        }
        return static::where($filter)->find();
    }
}