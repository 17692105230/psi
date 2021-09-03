<?php

namespace app\web\controller;

use app\common\library\wechat\wow\Order;
use app\common\utils\ToolHelper as ToolHelper;
use app\Request;
use app\web\model\SaleOrders;
use app\web\model\WXPowerNode as WXPowerNodeModel;
use think\cache\driver\Redis;
use app\web\model\User as UserModel;
use app\web\model\Role as RoleModel;

/**
 * 通用
 * Class Common
 * @package app\web\controller
 */
class Common extends Controller
{
    /**
     * 用户权限
     * @param Request $request
     * @return mixed
     */
    public function permission(Request $request)
    {
        $token = $request->get('token');
        $code = 9999;
        $redis = new Redis();
        $user_id = $redis->get("user:token:{$token}");
        if (!$user_id) {
            return $this->renderError("登录失效", $code);
        }
        // 查询角色ids
        $user_model = new UserModel();
        $fields = ['user_id', 'user_name', 'user_phone', 'user_idcode', 'com_id', 'open_id', 'user_role_ids'];
        $user = $user_model->getOne(['user_id' => $user_id], $fields);
        $user['user_phone'] = ToolHelper::phone_encryption($user['user_phone']);
        if (!$user) {
            return $this->renderError("用户不存在", $code);
        }
        $role_ids = array_filter(explode(',', $user['user_role_ids']));
        $role_model = new RoleModel();
        $user['is_super_admin'] = 0;
        if (in_array($role_model::SUPER_ADMIN_ROLE_ID, $role_ids)) {
            // 超级管理员
            $user['is_super_admin'] = 1;
            return $this->renderSuccess($user);
        }
        // 查询角色
        $role_list = $role_model->getList([['role_id', 'in', $role_ids]], ['wx_power_node'])->toArray();
        $node_list = []; // 权限点id列表
        array_map(function ($role) use (&$node_list) {
            array_map(function ($node) use (&$node_list) {
                array_push($node_list, $node);
            }, array_filter(explode(',', $role['wx_power_node'])));
        }, $role_list);
        // 查询权限点
        $wx_power_node_model = new WXPowerNodeModel();
        $fields = ['id', 'parent_id', 'node_name', 'node_url'];
        $not_allow_power_list = $wx_power_node_model->getList([['id', 'not in', $node_list]], $fields)->toArray();
        $user['node_list'] = $not_allow_power_list;
        $all_power_list = $wx_power_node_model->getList([], $fields);
        $user['all_node_list'] = $all_power_list;
        return $this->renderSuccess($user);
    }

    /**
     * 销售额
     * @return mixed
     */
    public function sale_volume()
    {
        $sale_model = new SaleOrders();
        $data = $sale_model->sale_volume($this->com_id);
        return $this->renderSuccess($data);
    }

    /**
     * 咨询
     * @return mixed
     */
    public function news()
    {
        $res = [
            'title' => '新品第二代升势发售,详情可点击查看'
        ];
        return $this->renderSuccess($res);
    }
}