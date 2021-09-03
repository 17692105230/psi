<?php


namespace app\web\model;

use app\common\model\web\Role as RoleModel;

class Role extends RoleModel
{
    /** @var int 超级管理员 角色id=1 */
    public const SUPER_ADMIN_ROLE_ID = 1;

    /** @var int 采购、库管 */
    public const PURCHASE_ADMIN_ROLE_ID = 25;
    /** @var int 销售、员工 */
    public const SALE_ADMIN_ROLE_ID = 27;

    /**
     * 获取角色列表
     * @param $com_id
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get_role_list($com_id)
    {
        return $this->where(['com_id' => intval($com_id)])->whereOr([['role_id', 'in', [
            self::SUPER_ADMIN_ROLE_ID,
            self::PURCHASE_ADMIN_ROLE_ID,
            self::SALE_ADMIN_ROLE_ID
        ]]])->select();
    }

}