<?php


namespace app\web\validate;


use think\Validate;

class Size extends Validate
{
    protected $regex = ['size_code'=>'[[a-zA-Z]{1,}[0-9]{1,}|[0-9]{1,}[a-zA-Z]{1,}]'];
    protected $rule = [
        'lock_version' => 'require',
        'size_name' => 'require',
        'size_id' => 'require',
        'size_code' => 'require|regex:size_code',
    ];

    protected $message = [
        'lock_version.require' => '版本信息不能为空',
        'size_name.require' => '尺码名称不能为空',
        'size_id.require' => 'id不能为空',
        'size_code.require' => '编码不能为空',
        'size_code.regex' => '编码格式必须是英文+数字',
    ];

    protected $scene = [
        'add' => ['size_name','size_code'],
        'edit'=>['size_id','lock_version','size_code'],
        'del' => ['size_id','lock_version'],
    ];
}