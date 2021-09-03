<?php
/**
 * 盘点单
 */

namespace app\web\validate;

use think\Validate;

class InventoryOrders extends Validate
{
    protected $rule = [
        'orders_id|盘点单ID'       =>  'require|egt:1',
        'warehouse_id|仓库ID'       => 'require|egt:1',
        'goods_number|商品总数'     => 'require',
        'goods_plnumber|盈亏数量'   => 'require',
        'user_id|制单人'  => 'require',
        'orders_status|盘点单状态' => 'require|in:1,9',
        'orders_date|盘点日期' => 'require',
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
            'warehouse_id',
            'goods_number',
            'goods_plnumber',
            'user_id',
            'orders_status',
            'orders_date',
            'com_id',
        ],
        'edit' => [
            'orders_id',
            'warehouse_id',
            'goods_number',
            'goods_plnumber',
            'user_id',
            'orders_status',
            'orders_date',
            'com_id',
//            'shop_id',
        ],
    ];
}