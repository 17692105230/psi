<?php
/**
 * Created by PhpStorm.
 * User: zll
 * Date: 2021/5/24
 * Time: 15:53
 */

namespace app\web\validate;


use think\Validate;

class StoreValidate extends Validate
{
    protected $regex = ['size_code'=>'[[a-zA-Z]{1,}[0-9]{1,}|[0-9]{1,}[a-zA-Z]{1,}]'];
    protected $rule = [
//        'com_id' => 'require',
        'warehouse_id' => 'require',
        'search_keyword' => 'require',
    ];

    protected $message = [
//        'com_id.require' => '所属企业不能为空',
        'warehouse_id.require' => '请选择仓库',
        'search_keyword.require' => '请填写商品编号',
    ];

    protected $scene = [
        'search' => [
//            'com_id',
            'warehouse_id',
            'search_keyword'
        ],
    ];
}