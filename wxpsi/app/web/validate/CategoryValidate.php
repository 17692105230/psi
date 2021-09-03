<?php


namespace app\web\validate;
use think\Validate;

class CategoryValidate extends Validate
{

    protected $rule = [
        'lock_version' => 'require',
        'category_id' => 'require',
        'category_name' => 'require',


    ];

    protected $message = [
        'lock_version.require' => '版本信息不能为空',
        'category_id.require' => 'id不能为空',
        'category_name.require' => '类别名称不能为空',
    ];

    protected $scene = [
        'add' => ['category_name'],
        'edit' => ['category_id','lock_version','category_name'],
        'del' => ['category_id','lock_version'],
    ];
}