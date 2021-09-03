<?php


namespace app\web\validate;

use think\Validate;

class Dict extends Validate
{
    protected $rule = [
        'lock_version' => 'require',
        'dict_id' => 'require',
        'dict_type' => 'require',
    ];

    protected $message = [
        'lock_version.require' => '版本信息不能为空',
        'dict_id.require' => 'id不能为空',
        'dict_type.require' => '字典类型不能为空',
    ];

    protected $scene = [
        'add' => ['dict_type'],
        'edit' => ['dict_id','lock_version'],
        'del' => ['dict_id','lock_version'],
    ];
}