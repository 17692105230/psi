<?php
/**
 * 销售单
 */

namespace app\web\validate;

use think\Validate;

class SaleOrders extends Validate
{
    protected $rule = [
        'details'       => 'require',
        'client_id'     => 'require|egt:1',
        'salesman_id'   => 'require|egt:1',
        'warehouse_id'  => 'require|egt:1',
        'orders_pmoney' => 'require',
        'orders_rmoney' => 'require',
        'goods_number'  => 'require',
    ];
    protected $message = [
        'client_id.require'     => '客户ID不能为空',
        'salesman_id.require'   => '销售员ID不能为空',
        'warehouse_id.require'  => '仓库ID不能为空',
    ];
    protected $scene = [
        'add' => [
            'details',
            'client_id',
            'salesman_id',
            'warehouse_id',
            'orders_pmoney',
            'orders_rmoney',
            'goods_number',
        ],
    ];
}