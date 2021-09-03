<?php


namespace app\web\validate;


use think\Validate;

class Color extends Validate
{
    protected $regex = ['color_code'=>'[[a-zA-Z]{1,}[0-9]{1,}|[0-9]{1,}[a-zA-Z]{1,}]'];
    protected $rule = [
        'lock_version' => 'require',
        'color_name' => 'require',
        'color_id' => 'require',
        'color_code' => 'require|regex:color_code',
    ];

    protected $message = [
        'lock_version.require' => '版本信息不能为空',
        'color_name.require' => '颜色名称不能为空',
        'color_id.require' => 'id不能为空',
        'color_code.require' => '编码不能为空',
        'color_code.regex' => '编码格式必须是英文+数字',
    ];

    protected $scene = [
        'add' => ['color_name','color_code'],
        'edit'=>['color_id','lock_version','color_code'],
        'del' => ['color_id','lock_version'],
    ];
}