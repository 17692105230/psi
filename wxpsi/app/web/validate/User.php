<?php


namespace app\web\validate;


use think\Validate;

class User extends Validate
{
    protected $rule = [
        'user_phone' => ['require','regex'=>'/^0?(13|14|15|17|18)[0-9]{9}$/'],
        'user_login_password' => 'require',
        'user_login_password2' => 'require',
        'user_id' => 'require',
    ];
    protected $message = [
        'user_phone.require' => '手机号不能为空',
        'user_phone.regex' => '手机号格式不正确',
        'user_login_password2.require' => '再次输入密码不能为空',
    ];
    protected $scene = [
        'add' => ['user_phone','user_login_password'],
        'edit' => ['user_id','user_phone','user_login_password']
    ];
}