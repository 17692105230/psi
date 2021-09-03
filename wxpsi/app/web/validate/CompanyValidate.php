<?php


namespace app\web\validate;

use think\Validate;

class CompanyValidate extends Validate
{
    protected $rule = [
        'company_name' => 'require|length:1,50',
        'company_phone' => 'require|length:11|number', //手机号
        'company_contact' => 'require|length:1,50',
        'user_name' => 'require|length:1,50',
        'password' => 'require|length:1,50',
        'idcode' => 'require',
        'user_phone' => 'require|length:11|number', //手机号
    ];

    protected $message = [
        'company_name.require' => '企业名字长度为1~50个字符',
        'company_phone.require' => '企业手机号格式不正确',
        'company_contact.require' => '企业联系人格式不正确',
        'user_name.require' => '用户名格式不正确',
        'password.require' => '密码格式不正确',
        'idcode.require' => '身份证格式不正确',
        'user_phone.require' => '用户手机号格式不正确'
    ];

    protected $scene = [
        'add' => [
            'company_name',
            'company_phone',
            'company_contact',
            'user_name',
            'password',
            'idcode',
            'user_phone'
        ],
    ];
}