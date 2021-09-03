<?php
/**
 * Created by PhpStorm.
 * User: zll
 * Date: 2021/6/2
 * Time: 9:44
 */

namespace app\web\validate;

//调拨单验证器
use think\Validate;

class TransferOrders extends Validate
{
    protected $rule = [
        'orders_id|调拨单ID'       =>      'require|egt:1',
        'out_warehouse_id|调出仓库'       => 'require|egt:1',
        'in_warehouse_id|调入仓库'       => 'require|egt:1|different:out_warehouse_id',
        'goods_number|商品总数'     => 'require',
        'orders_date|调拨日期' => 'require',
        'user_id|制单人'  => 'require',
        'orders_status|盘点单状态' => 'require|in:0,9',
        'com_id|企业ID'   => 'require',
        'shop_id|门店ID'   => 'require',
    ];
//    protected $message = [
//        'client_id.require'     => '客户ID不能为空',
//        'salesman_id.require'   => '销售员ID不能为空',
//        'warehouse_id.require'  => '仓库ID不能为空',
//    ];
    protected $scene = [
        'add' => [
            'out_warehouse_id',
            'in_warehouse_id',
            'goods_number',
            'orders_date',
            'user_id',
            'orders_status',
            'com_id',
        ],
        'edit' => [
            'orders_id',
            'out_warehouse_id',
            'in_warehouse_id',
            'goods_number',
            'orders_date',
            'user_id',
            'orders_status',
            'com_id',
//            'shop_id',
        ]
    ];
}