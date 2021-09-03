<?php


namespace app\web\validate;


use think\Validate;

class Settlement extends Validate
{
    protected $rule = [
        'settlement_id'=>'require',
        'settlement_name' => 'require',
        'settlement_code' => 'number|require',
        'account_holder' => 'require',
        'settlement_money' => 'require',
        'lock_version' => 'require'
    ];
    protected $message = [
        'settlement_id.require' => 'ID不能为空',
        'settlement_name.require' => '账户名称不能为空',
        'settlement_code.require' => '账号不能为空',
        'settlement_code.number' => '账号必须为数字',
        'account_holder.require' => '开户人不能为空',
        'settlement_money.require' => '账户金额不能为空',
        'lock_version.require' => '版本号不能为空'
    ];
    protected $scene = [
        'add' => ['settlement_name'],
        'edit' => ['settlement_id','lock_version'],
        'del' => ['settlement_id','lock_version']
    ];
}