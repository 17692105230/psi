<?php


namespace app\web\model;

use app\common\model\web\Color as ColorModel;

class Color extends ColorModel
{
    /**
     * 函数描述: 加载数据
     * @param $where
     * @param string $field
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2021/1/7
     * Time: 11:16
     * @author mll
     */
    public function loadData($where, $field = '*')
    {
        $model = $this->where($where)->field($field)->select();
        return $model;
    }

    /**
     * 函数描述:根据条件查询
     * @param $where
     * Date: 2021/1/7
     * Time: 11:18
     * @author mll
     */
    public function findDataByExample($where)
    {
        return $this->where($where)->find();
    }

    /**
     * 函数描述:添加颜色
     * @param $data
     * Date: 2021/1/7
     * Time: 11:16
     * @author mll
     */
    public function saveColor($data)
    {
        return $this->save($data);
    }

    /**
     * 函数描述:修改颜色
     * @param $where
     * @param $updata
     * @return Color
     * Date: 2021/1/7
     * Time: 17:21
     * @author mll
     */
    public function editColor($where, $updata)
    {
        $model = $this->where($where)->update($updata);
        return $model;
    }

    /**
     * 函数描述:删除颜色
     * @param $where
     * @return bool
     * Date: 2021/1/7
     * Time: 17:22
     * @author mll
     */
    public function delColor($where)
    {
        return $this->where($where)->delete();
    }

    public function likeColorQuery($where)
    {

       return $this
            ->where('com_id', $where['com_id'])
            ->when(isset($where['color_name']) && $where['color_name'], function ($query) use ($where) {
                $query->where('color_name', 'like', '%' . $where['color_name'] . '%');
            })
            ->select();
    }


}
