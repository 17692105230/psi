<?php


namespace app\web\validate;


use think\Validate;

class Supplier extends Validate
{
    protected $rule = [
        'lock_version' => 'require',
        'supplier_id'=>'require',
        'supplier_name' => 'require',
        'supplier_director' => 'require',
        'supplier_discount' => 'require',
        'supplier_mphone' => 'require|number|length:11',
    ];

    protected $message = [
        'supplier_id.require'=>'提供有效的供应商id',
        'lock_version.require' => '版本信息不能为空',
        'supplier_name.require' => '供应商名称不能为空',
        'supplier_director.require' => '负责人不能为空',
        'supplier_mphone.require' => '联系电话不能为空',
        'supplier_mphone.number' => '联系电话必须为数字',
        'supplier_mphone.length' => '电话长度必须为11位'
    ];

    protected $scene = [
        'add' => ['supplier_name','supplier_director','supplier_mphone'],
        'edit' =>['supplier_id','lock_version','supplier_name','supplier_director','supplier_mphone'],
        'del' => ['supplier_id','lock_version']
    ];
}