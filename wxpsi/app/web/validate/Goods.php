<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/4
 * Time: 18:01
 */

namespace app\web\validate;


use think\Validate;

class Goods extends Validate
{
    protected $rule = [
        'goods_name' => 'require|length:1,50',
        'goods_code' => 'require',
        'goods_pprice' => 'require|gt:0',
        'goods_rprice' => 'require|gt:0',
        'lock_version' => 'number|gt:-1',
        'category_id' => 'require',
        'color_id' => 'require',
        'size_id' => 'require'
    ];

    protected $message = [
        'category_id.require' => '商品类别不能为空~',
        'goods_code.require' => '商品货号不能为空',
        'goods_name.require' => '名称必须填写',
        'goods_pprice.require' => '采购价必须填写',
        'goods_pprice.number' => '采购价必须为数字~~',
        'goods_pprice.gt' => '采购价必须大于 0~~',
        'goods_rprice.require' => '零售价必须填写',
        'goods_rprice.number' => '零售价必须为数字~~',
        'goods_rprice.gt' => '零售价必须大于 0~~',
        'lock_version.number' => '系统参数错误~~',
        'lock_version.gt' => '系统参数错误~~',
        'color_id.require' => '颜色不能为空',
        'size_id.require' => '尺寸不能为空'
    ];

    protected $scene = [
        'add' => ['goods_id', 'goods_name', 'goods_code', 'goods_pprice',
            'goods_rprice', 'color_id', 'size_id'],
        'edit' => [
            'goods_id', 'goods_name', 'goods_code', 'goods_pprice',
            'goods_rprice', 'color_id', 'size_id', 'lock_version'
        ],
    ];
}