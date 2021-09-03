<?php


namespace app\web\validate;


use think\Validate;

class ClientBase extends Validate
{
    protected $rule = [
        'account_money' => 'require',
        'client_code' => 'require',
        'client_name' => 'require',
        'client_category_id' => 'require',
        'client_discount' => 'require',
        'client_phone' => 'require',
        'client_status' => 'require',
    ];
    protected $message = [
        'account_money.require' => '账户金额不能为空',
        'client_code.require' => '客户编号不能为空',
        'client_name.require' => '客户名称不能为空',
        'client_category_id.require' => '客户类别不正确',
        'client_discount.require' => '默认折扣不正确',
        'client_phone.require' => '客户电话不正确',
        'client_status.require' => '客户状态不正确',
    ];
    protected $scene = [
        'save' => [
            'account_money',
            'client_code',
            'client_name',
//            'client_category_id',
            'client_discount',
            'client_phone',
            'client_status',
        ],
    ];
}