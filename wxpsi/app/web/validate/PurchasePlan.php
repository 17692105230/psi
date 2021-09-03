<?php


namespace app\web\validate;


class PurchasePlan extends BaseValidate
{
    protected $rule = [
        'orders_code' => 'require',
        'supplier_id' => 'require',
        'settlement_id' => 'require',
        'data_update' => 'require',
        'data_insert' => 'require',
        'data_delete' => 'require',
        'orders_date' => 'require',
        'lock_version' => 'require',

        'details' => 'require',
        'warehouse_id' => 'require',
        'orders_remark' => 'require',
        'orders_status' => 'require',
        'goods_number' => 'require',
        'orders_pmoney' => 'require',
    ];

    protected $message = [
        'orders_code.require' => '采购定单号不能为空',
        'supplier_id.require' => '供应商不能为空',
        'settlement_id.require' => '结算账户不能为空',
        'data_update.require' => '修改订单修改内容不能为空',
        'data_insert.require' => '修改订单添加内容不能为空',
        'data_delete.require' => '修改订单删除内容不能为空',
        'orders_date.require' => '订单日期不能为空',
        'lock_version.require' => '版本信息不能为空',
        'details.require' => '采购单详细内容不能为空',
        'orders_remark.require' => '单据备注不能为空',
        'goods_number.require' => '单据商品数量不能为空',
        'orders_pmoney.require' => '单据备注不能为空',
    ];

    protected $scene = [
        'details' => ['orders_code'],
        'delete' => ['orders_code'],
        'create' => [
            'details', 'supplier_id','warehouse_id', 'orders_date',
            'orders_status','goods_number','orders_pmoney'
        ],
        'update' => ['details', 'supplier_id','warehouse_id', 'orders_date', 'orders_remark','orders_status','lock_version','orders_code'],
    ];
}