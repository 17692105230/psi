<?php


namespace app\web\validate;


use think\Validate;

class Organization extends Validate
{
    protected $rule = [
        'org_id' => 'require',
        'org_name' => 'require',
        'org_head' => 'require',
        'org_phone' => 'require|number|length:11',
        'lock_version' => 'require|number',
    ];
    protected $message = [
        'org_id.require' => 'ID不能为空',
        'org_name.require' => '仓库名称不能为空',
        'org_head.require' => '负责人不能为空',
        'lock_version.require' => '版本信息不能为空',
        'lock_version.number' => '版本信息只能是数字',
        'org_phone.require' => '联系人手机号不能为空',
        'org_phone.number' => '联系人手机号只能为数字',
        'org_phone.length' => '联系人手机号长度必须为11位'
    ];
    protected $scene = [
        'add' => ['org_name','org_head','org_phone'],
        'edit' => ['org_name','org_head','org_phone','org_id','lock_version'],
        'del' => ['org_id','lock_version'],
    ];
}