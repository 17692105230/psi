<?php


namespace app\web\validate;

use think\Validate;
class MemberCardValidate extends Validate
{
    protected $rule = [
        'oid' => 'require',     //主键id
        'openid' => 'require',    //微信openID
        'cardid' => 'require',  //会员卡类型ID
        'usercardcode' => 'require',//会员卡编号
        'member_name' => 'require', //会员姓名
        'member_sex' => 'require',  //会员性别
        'member_phone' => 'require|length:11|number',//手机号
        'lock_version' => 'require'//锁版本
    ];
    protected $message = [
        'oid.require' => '会员卡id不能为空',
        'openid.require' => '微信openID不能为空',
        'cardid.require' => '会员卡类型不能为空',
        'usercardcode.require' => '会员卡编号不能为空',
        'member_name.require' => '会员姓名不能为空',
    ];
    protected $scene = [
        'add' =>['member_name','member_phone'],
        'edit' => ['oid','lock_version'],
        'del' => ['oid','lock_version']
    ];
}