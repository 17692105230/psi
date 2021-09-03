<?php


namespace app\web\model;

use app\common\model\web\Goods as GoodsModel;
use think\db\Query;
use think\facade\Db;

class Goods extends GoodsModel
{

    /**
     * 函数描述:获取商品列表信息
     * @param $where
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2021/1/7
     * Time: 16:34
     * @author mll
     */
    public function getGoodsWX($where,$whereOr = "", $rows=10)
    {
         return  $this
            ->alias('g')
            ->with(['images', 'detail'])
            ->where($where)
            ->where(function ($query) use($whereOr){
                $query->whereOr($whereOr);
            })
            ->order('g.create_time', 'desc')
            ->field('g.*')
            ->paginate($rows);
    }

    public function puchaseOrdersList($where)
    {
        //如果筛选有仓库条件,查出仓库包含的商品id数组,作为查询条件
        $where_org_ids = [];
        if(isset($where['org_id']) && $where['org_id']){
            $where_org_ids = (new GoodsStock())->where("warehouse_id","=",$where['org_id'])->group("goods_id")->column("goods_id");
        }
        //闭包查询
        $query = $this->alias('g')
            ->with(['detail','images'])
            ->where('com_id',$where['com_id'])
            ->where('goods_status', '=', 1)
            ->when(isset($where['goods_name']) && $where['goods_name'],function ($query) use ($where){
                $query->where(function ($query) use ($where) {
                    $query->whereOr('goods_name','like','%'.$where['goods_name'].'%');
                    $query->whereOr('goods_code','like','%'.$where['goods_name'].'%');
                    $query->whereOr('goods_barcode','like','%'.$where['goods_name'].'%');
                });

            })
            ->when(isset($where['org_id']) && $where['org_id'],function($query) use ($where,$where_org_ids){
                $query->where('goods_id','in',$where_org_ids);
            })
        ;
        return $query->field("g.*,false as 'colorShow'")->select();
    }

    /**
     * 查询商品列表 返回列表是以颜色尺码为准的列表,用于盘点单使用
     * zll
     * Date: 2021/5/26
     * Time: 10:19
     * @param $where
     * @return \think\Collection
     */
    public function orderListCell($where=[]){
        $gsModel = new GoodsStock();

        if(isset($where['goods_name']) && !empty($where['goods_name'])){
            $gsModel = $gsModel->where("g.goods_name","like","%".$where['goods_name']."%");
        }
        $list = $gsModel->alias("gs")
            ->leftJoin('hr_goods g','g.goods_id = gs.goods_id')
            ->leftJoin('hr_goods_assist ga','ga.goods_id = gs.goods_id')
            ->leftJoin("hr_size s","s.size_id = gs.size_id")
            ->leftJoin("hr_color c","c.color_id = gs.color_id")
            ->field("g.*,ga.assist_url,s.size_name,s.size_id,c.color_name,c.color_id")
            ->paginate(10)
        ;
        return $list->toArray();
    }


    /**
     * 函数描述:删除商品
     * @param $where
     * @return bool
     * Date: 2021/1/7
     * Time: 16:34
     * @author mll
     */
    public function deleteGoods($where)
    {
        return $this->where($where)->delete();
    }

    /**
     * 函数描述:查找一条
     * @param $where
     * @param $field
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2021/1/7
     * Time: 16:34
     * @author mll
     */
    public function findGoods($where, $field)
    {
        return $this->where($where)->field($field)->find();
    }

    /**
     * 函数描述:更改商品
     * @param $where
     * @param $update
     * @return Goods
     * Date: 2021/1/7
     * Time: 16:34
     * @author mll
     */
    public function updateGoods($update,$where)
    {
       return $this->update($update, $where);
    }

    /**
     * 函数描述: 模糊查询
     * @param $where
     * @return mixed
     * Date: 2021/1/27
     * Time: 11:14
     * @author mll
     */
    public function likeQuery($where)
    {
        return $this->alias('g')
            ->with(['images','detail'])
            ->join('hr_goods_details d', 'd.goods_id = g.goods_id', 'left')
            ->join('hr_color c', 'c.color_id = d.color_id', 'left')
            ->join('hr_size s', 's.size_id = d.size_id', 'left')
            ->where('g.com_id',$where['com_id'])
            ->when(isset($where['goods_code']) && $where['goods_code'],function ($query) use ($where){
                $query->where('g.goods_code', 'like', '%'.$where['goods_code'].'%');
            })
            ->when(isset($where['color_name']) && $where['color_name'],function ($query) use ($where){
                $query->where('c.color_name','like','%'.$where['color_name'].'%');
            })
            ->when(isset($where['size_name']) && $where['size_name'],function ($query) use ($where){
                $query->where('s.size_name','like','%'.$where['size_name'].'%');
            })
            ->field('g.*,d.*,s.size_id,c.color_id')
            ->select();
    }





}