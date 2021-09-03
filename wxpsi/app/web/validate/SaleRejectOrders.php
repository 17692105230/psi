<?php


namespace app\web\validate;


use think\Validate;

class SaleRejectOrders extends Validate
{
    protected $rule = [
        'client_id' => 'require',
        'salesman_id' => 'require',
        'warehouse_id' => 'require',
        'orders_date' => 'require|dateFormat:Y-m-d',
        'orders_rmoney' => 'require|number',
        'settlement_id' => 'require',
        'lock_version' => 'require',
        'orders_emoney' => 'require|number',
        'orders_pmoney' => 'require|number',
        'data_insert' => 'require',
        'orders_code' => 'require',
        'data_update' => 'require',
        'data_delete' => 'require',
        'details' => 'require',
    ];
    protected $message = [
        'client_id.require' => '客户不能为空',
        'salesman_id.require' => '销售人员不能为空',
        'warehouse_id.require' => '仓库不能为空',
        'orders_date.require' => '日期不能为空',
        'orders_date.dateFormat' => '日期格式不正确',
        'orders_rmoney.require' => '实收金额不能为空',
        'orders_rmoney.number' => '实收金额必须为数字',
        'settlement_id.require' => '结算账户不能为空',
        'lock_version.require' => '版本信息不能为空',
        'orders_emoney.require' => '抹零金额不能为空',
        'orders_emoney.number' => '抹零金额必须为数字',
        'orders_pmoney.require' => '应付金额不能为空',
        'orders_pmoney.number' => '应付金额必须为数字',
        'data_insert.require' => '添加数据不能为空',
        'data_update.require' => '更新数据不能为空',
        'data_delete.require' => '删除数据不能为空',
        'details.require' => '数据不能为空',
    ];
    protected $scene = [
        'add' => [
            'client_id', 'salesman_id',
            'warehouse_id', 'orders_date',
            'settlement_id',
        ],
        'del' => [
            'orders_code'
        ],
        'repeal' => ['lock_version','orders_code']
    ];


}