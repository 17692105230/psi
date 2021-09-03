<?php


namespace app\web\model;

use app\common\model\web\Size as SizeModel;

class Size extends SizeModel
{
    /**
     * 函数描述:加载尺码列表
     * @param $where
     * @param string $field
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2021/1/8
     * Time: 10:05
     * @author mll
     */
    public function loadData($where, $field = '*')
    {
        return $this->where($where)->field($field)->select();
    }

    /**根据条件查找一条数据
     * 函数描述:
     * @param $where
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2021/1/8
     * Time: 11:06
     * @author mll
     */

    public function findDataByExample($where)
    {
        return $this->where($where)->find();
    }

    /**
     * 函数描述:添加尺码
     * @param $data
     * @return bool
     * Date: 2021/1/8
     * Time: 10:06
     * @author mll
     */
    public function saveSize($data)
    {
        return $this->save($data);
    }

    /**
     * 函数描述:修改尺码
     * @param $where
     * @param $updata
     * @return Size
     * Date: 2021/1/8
     * Time: 11:11
     * @author mll
     */
    public function editSize($where,$updata){
        $model = $this->where($where)->update($updata);
        return $model;
    }

    /**
     * 函数描述:删除尺码
     * @param $where
     * @return bool
     * Date: 2021/1/8
     * Time: 11:11
     * @author mll
     */
    public function delSize($where){
        return $this->where($where)->delete();
    }
}