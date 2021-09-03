<?php


namespace app\web\model;

use app\common\model\web\ClientBase as ClientBseModel;
use app\web\model\ClientBase as ClientBaseModel;

class ClientBase extends ClientBseModel
{

    /**
     * 根据条件查询客户
     * @param array $where
     * @param array $fields
     * @param string $clientIds
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getListByWhere($where = [], $clientIds = "", $fields = []) {
        $clientModel = new ClientBaseModel();
        return json_decode($clientModel->where($where)->whereIn('client_id', $clientIds)->field($fields)->select(), true);
    }

}