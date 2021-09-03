<?php


namespace app\web\controller;

use app\Request;
use app\web\model\WXPowerNode as WXPowerNodeModel;
use app\web\validate\RoleValidate;
use app\web\model\Role as RoleModel;
use app\web\model\User as UserModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 权限
 * Class Power
 * @package app\web\controller|
 */
class Power extends Controller
{
    /**
     * 节点列表
     * @return mixed
     */
    public function node_list()
    {
        $node_model = new WXPowerNodeModel();
        $list = json_decode($node_model->getList([]), true);
        $result = $node_model->_recursive_node($list);
        return $this->renderSuccess($result);
    }

    /**
     * 角色列表
     * @return mixed
     */
    public function role_list()
    {
        $role_model = new RoleModel();
        try {
            $list = $role_model->get_role_list($this->com_id);
            $result = array_map(function ($v) use ($role_model) {
                $v['wx_power_node'] = array_filter(explode(',', $v['wx_power_node']));
                $v['disabled'] = false;
                if (in_array($v['role_id'], [$role_model::SUPER_ADMIN_ROLE_ID, $role_model::PURCHASE_ADMIN_ROLE_ID, $role_model::SALE_ADMIN_ROLE_ID])) {
                    $v['disabled'] = true;
                }
                return $v;
            }, json_decode($list, true));
            return $this->renderSuccess($result);
        } catch (DataNotFoundException | ModelNotFoundException | DbException $e) {
            return $this->renderError($e->getMessage());
        }
    }

    /**
     * 添加角色
     * @param Request $request
     * @return mixed
     */
    public function add_role(Request $request)
    {
        $params = $request->post();
        $role_validate = new RoleValidate();
        if (!$role_validate->scene("add")->check($params)) {
            return $this->renderError($role_validate->getError());
        }
        $role_name = trim($params['role_name']);
        $role_model = new RoleModel();
        $exist = $role_model->getOne(['com_id' => $this->com_id, 'role_name' => $role_name]);
        if ($exist) {
            return $this->renderError("角色名已经存在");
        }
        $wx_power_node = json_decode(trim($params['wx_power_node']), true);
        $node = $wx_power_node ? ',' . implode(',', $wx_power_node) . ',' : '';
        $row = [
            'role_name' => $role_name,
            'role_remark' => trim($params['role_remark']),
            'wx_power_node' => $node,
            'create_time' => time(),
            'com_id' => $this->com_id
        ];
        $res = $role_model->insert($row);
        if ($res) {
            return $this->renderSuccess();
        }
        return $this->renderError("角色添加失败");
    }

    /**
     * 编辑角色
     * @param Request $request
     * @return mixed
     */
    public function edit_role(Request $request)
    {
        $params = $request->post();
        $role_validate = new RoleValidate();
        if (!$role_validate->scene("edit")->check($params)) {
            return $this->renderError($role_validate->getError());
        }
        $role_name = trim($params['role_name']);
        $role_id = intval($params['role_id']);
        $role_model = new RoleModel();
        $where = [
            ['com_id', '=', $this->com_id],
            ['role_name', '=', $role_name],
            ['role_id', '<>', $role_id]
        ];
        $exist = $role_model->getOne($where);
        if ($exist) {
            return $this->renderError("角色名已经存在");
        }
        $role_remark = trim($params['role_remark']);
        $wx_power_node = json_decode(trim($params['wx_power_node']), true);
        $node = $wx_power_node ? ',' . implode(',', $wx_power_node) . ',' : '';
        $ok = $role_model->update([
            'role_name' => $role_name,
            'role_remark' => $role_remark,
            'wx_power_node' => $node
        ], ['role_id' => $role_id]);
        if (!$ok) {
            return $this->renderError("角色更新失败");
        }
        return $this->renderSuccess();
    }

    /**
     * 删除角色
     * @param Request $request
     * @return mixed
     */
    public function delete_role(Request $request)
    {
        $params = $request->post();
        $role_id = intval($params['role_id']);
        $role_model = new RoleModel();
        $role = $role_model->getOne(['role_id' => $role_id, 'com_id' => $this->com_id]);
        if (!$role) {
            return $this->renderError("role_id角色id不存在");
        }
        $user_model = new UserModel();
        $exist = $user_model->getOne([['user_role_ids', 'like', "%,{$role_id},%"]]);
        if ($exist) {
            return $this->renderError("该角色已绑定用户，不能删除");
        }
        $ok = $role_model->delByWhere(['role_id' => $role_id]);
        if (!$ok) {
            return $this->renderError("删除失败");
        }
        return $this->renderSuccess();
    }

    /**
     * 角色详情
     * @param Request $request
     * @return mixed
     */
    public function role_detail(Request $request)
    {
        $params = $request->get();
        $role_id = intval($params['role_id']);
        $role_model = new RoleModel();
        $role = $role_model->getOne(['role_id' => $role_id]);
        if (!$role) {
            return $this->renderError("角色不存在");
        }
        $role['wx_power_node'] = array_filter(explode(',', $role['wx_power_node']));
        $role['disabled'] = false;
        if (in_array($role['role_id'], [$role_model::SUPER_ADMIN_ROLE_ID, $role_model::PURCHASE_ADMIN_ROLE_ID, $role_model::SALE_ADMIN_ROLE_ID])) {
            $role['disabled'] = true;
        }
        return $this->renderSuccess($role);
    }
}