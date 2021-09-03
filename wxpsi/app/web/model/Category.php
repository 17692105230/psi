<?php


namespace app\web\model;

use app\common\model\web\Category as CategoryModel;

class Category extends CategoryModel
{

    /**
     * 函数描述:加载列表
     * @param $where
     * Date: 2021/1/5
     * Time: 11:09
     * @param string $field
     * @author mll
     */
   public function loadData($where , $field = '*'){
       $model = $this->where($where)->field($field)->select();

       return $model;
   }

    /**
     * 函数描述:查询pid 和 id关系
     * @param $where
     * @param string $field
     * Date: 2021/1/5
     * Time: 15:04
     * @author mll
     */
   public function findCategoryId($where, $field = 'category_id,category_pid,category_name'){
        $model = $this->where($where)->field($field)->find();
        return $model;
   }

    /**
     * 函数描述:查找一条
     * @param $where
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2021/1/7
     * Time: 16:39
     * @author mll
     */
    public function findCategoryByExample($where){
        return $this->where($where)->find();
    }
    /**
     * 函数描述:添加类别
     * @param $data
     * @return bool
     * Date: 2021/1/7
     * Time: 10:10
     * @author mll
     */
   public function saveCategory($data){
       return $this->save($data);
   }

    /**
     * 函数描述:修改商品
     * @param $where
     * @param $updata
     * Date: 2021/1/7
     * Time: 16:33
     * @author mll
     */
   public function editCategory($where,$updata){
       return $this->where($where)->update($updata);
   }

    /**
     * 函数描述:删除类别
     * @param $where
     * Date: 2021/1/7
     * Time: 17:16
     * @author mll
     */
   public function delCategory($where){
       return $this->where($where)->delete();
   }
}