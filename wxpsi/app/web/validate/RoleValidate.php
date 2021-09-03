<?php


namespace app\web\validate;

use think\Validate;
class RoleValidate extends Validate
{
    protected $rule = [
        'role_name' => 'require',
        'wx_power_node' => 'require',
        'role_id' => 'require'
    ];
    protected $message = [
        'role_name.require' => '角色名称必填',
        'wx_power_node.require' => '权限节点必选',
        'role_id.require' => '角色ID必填'
    ];
    protected $scene = [
        'add' => ['role_name','wx_power_node'],
        'edit'=> ['role_name','wx_power_node','role_id']
    ];
}