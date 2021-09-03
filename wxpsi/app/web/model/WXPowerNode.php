<?php


namespace app\web\model;

use app\common\model\web\WXPowerNode as WXPowerNodeModel;

class WXPowerNode extends WXPowerNodeModel
{

    /**
     * 递归节点
     * @param $data array
     * @param $parent_id int
     * @return array
     */
    public function _recursive_node(array $data, $parent_id = 0): array
    {
        $result = [];
        foreach ($data as $v) {
            if ($v['parent_id'] != $parent_id) {
                continue;
            }
            $children = $this->_recursive_node($data, $v['id']);
            if ($children) {
                $v['children'] = $children;
            }
            array_push($result, $v);
        }
        return $result;
    }

}