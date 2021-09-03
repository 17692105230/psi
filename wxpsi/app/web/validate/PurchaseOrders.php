<?php


namespace app\web\validate;


use think\Validate;

class PurchaseOrders extends Validate
{
    protected $rule = [
        'supplier_id' => 'require|egt:1',
        'warehouse_id' => 'require|egt:1',
        //'settlement_id' => 'require|egt:1',
        'orders_pmoney' => 'require',
        'orders_rmoney' => 'require',
        'goods_number' => 'require',
        'other_type' => 'require',
        'other_money' => 'require',
        'erase_money' => 'require',
        'detail' => 'require',
    ];
    protected $message = [
        'supplier_id.require' => '供应商ID不能为空',
        'warehouse_id.require' => '仓库ID不能为空',
        //'settlement_id.require' => '结算账户ID不能为空',
        'orders_pmoney.require' => '应付金额不能为空',
        'orders_rmoney.require' => '实付金额不能空',
        'goods_number.require' => '商品数量不能为空',
        'other_type.require' => '其他费用不能为空',
        'other_money' => '其他金额不能为空',
        'erase_money' => '抹零金额不能为空',
        'detail,require' => '采购详情不能为空'
    ];
    protected $scene = [
        //'add' => ['supplier_id','warehouse_id','settlement_id','orders_pmoney','orders_rmoney','goods_number','list'],
        'add' => ['supplier_id','warehouse_id','settlement_id','orders_pmoney','orders_rmoney','goods_number','list'],
        'del' => ['lock_version','orders_code','orders_id'],
        'edit' => ['insert_detail','del_detail','edit_detail','lock_version','orders_code','orders_id'],
    ];
}