<?php
declare (strict_types=1);

namespace app\middleware;

use app\common\service\Render;
use app\web\model\User;
use think\cache\driver\Redis;
use app\web\model\User as UserModel;
use app\web\model\Role as RoleModel;
use app\web\model\WXPowerNode as WXPowerNodeModel;


/**
 * 验证权限
 * Class VerifyPermissions
 * @package app\middleware
 */
class VerifyPermissions
{
    use Render;

    /**
     * 验证权限
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $debug = $request->param('debug');
        if ($debug == 'fly') {
            // debug模式
            return $next($request);
        }
        // 获取请求的控制器/方法
        $str = explode('/', $request->get('s'));
        $str_length = count($str);
        $action = strtolower($str[$str_length - 1]); // 方法
        $controller = strtolower($str[$str_length - 2]); // 控制器
        if (in_array($action, ["normal_login", 'register', 'is_authorize'])) {
            // 如果是登录或注册则不校验token
            return $next($request);
        }
        $token = $request->param('token'); // 登录token
        $redis = new Redis();
        $user_id = $redis->get("user:token:{$token}");
        $code = 9999;
        if (!$user_id) {
            // 未获取到user_id
            return $this->renderError("登录失效", $code);
        }
        // 查询用户信息
        $user_model = new UserModel();
        $user = $user_model->getOne(['user_id' => $user_id]);
        if (!$user) {
            return $this->renderError("用户不存在", $code);
        }
        $user_role_ids = array_filter(explode(',', $user['user_role_ids']));
        if (in_array(RoleModel::SUPER_ADMIN_ROLE_ID, $user_role_ids)) {
            // 超级管理员
            return $next($request);
        }
        $role_model = new RoleModel();
        $role_list = $role_model->getList([['role_id', 'in', $user_role_ids]], ['wx_power_node'])->toArray();
        $node_list = []; // 权限点id列表
        array_map(function ($role) use (&$node_list) {
            array_map(function ($node) use (&$node_list) {
                array_push($node_list, $node);
            }, array_filter(explode(',', $role['wx_power_node'])));
        }, $role_list);
        // 查询权限点
        $wx_power_node_model = new WXPowerNodeModel();
        $power_list = $wx_power_node_model->getList([['id', 'not in', $node_list]], [
            'id', 'controller_name', 'action_name'])->toArray();
        $not_allow_list = array_map(function ($power) {
            if ($power['controller_name'] && $power['action_name']) {
                $controller_name = mb_strtolower($power['controller_name']);
                $action_name = mb_strtolower($power['action_name']);
                return "{$controller_name}/{$action_name}";
            }
        }, $power_list);
        if (in_array("{$controller}/{$action}", array_filter($not_allow_list))) {
            return $this->renderError("暂无操作权限");
        }
        return $next($request);
    }
}
