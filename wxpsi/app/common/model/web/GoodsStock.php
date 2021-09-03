<?php


namespace app\common\model\web;


use app\common\model\BaseModel;
use think\facade\Db;

class GoodsStock extends BaseModel
{
    protected $table="hr_goods_stock";
    protected $pk = "stock_id";

    public function goods(){
        return $this->hasOne(Goods::class,"goods_id","goods_id");
    }

    //zll 更新库存 盘点单
    public function updateForInventory($data){
        foreach ($data as $item){
            $gs = $this->where([
                "warehouse_id"=>$item['warehouse_id'],
                "goods_id"=>$item['goods_id'],
                "color_id"=>$item['color_id'],
                "size_id"=>$item['size_id'],
            ])->find();
            if(!$gs){
                $goods = (new Goods())->where("goods_id","=",$item['goods_id'])->find();
                //不存在就新增
                $this->insert(
                    [
                        "warehouse_id"=>$item['warehouse_id'],
                        "goods_id"=>$item['goods_id'],
                        "goods_code"=>$goods['goods_code'],
                        "color_id"=>$item['color_id'],
                        "size_id"=>$item['size_id'],
                        'stock_number'=>$item['stock_number'],
                        "lock_version"=>0,
                        "last_orders_code"=>$item['last_orders_code'],
                        "com_id"=>$item['com_id'],
                        "shop_id"=>$item['shop_id'],
                    ]
                );
            }else{
                static::update(
                    [
                        'stock_number'=>$item['stock_number'],//如果是增加,需要在构造数据用raw,不要在这里改raw,因为有些地方是直接更新数量的,例如盘点单
                        "lock_version"=>Db::raw("lock_version+1"),
                        "last_orders_code"=>$item['last_orders_code'],
                    ],
                    [
                        "warehouse_id"=>$item['warehouse_id'],
                        "goods_id"=>$item['goods_id'],
                        "color_id"=>$item['color_id'],
                        "size_id"=>$item['size_id'],
                    ]
                );
            }
        }
        return true;
    }

    //zll 更新库存 调拨单
//    public function updateForTransfer($data){
//        foreach ($data as $item){
//            static::update(
//                [
//                    'stock_number'=>$item['goods_number'],
//                    "lock_version"=>Db::raw("lock_version+1"),
//                    "last_orders_code"=>$item['order_code'],
//                ],
//                [
//                    "warehouse_id"=>$item['warehouse_id'],
//                    "goods_id"=>$item['goods_id'],
//                    "color_id"=>$item['color_id'],
//                    "size_id"=>$item['size_id'],
//                ]
//            );
//        }
//        return true;
//    }

    //根据条件查询商品库存列表  用于库存查询使用
    public function searchGoodsStock($where){
        //查出符合条件的库存商品列表
        $list = $this->alias("gs")
            ->join('hr_goods g', 'g.goods_id=gs.goods_id', 'left')
            ->when((isset($where['keyword']) && !empty($where['keyword'])),function($query)use($where){
                $query->where("g.goods_code|g.goods_name","like","%".$where['keyword']."%");
            })
            ->when(isset($where['org_ids']),function($query)use($where){
                $org_ids_arr = explode(",",$where['org_ids']);
                $query->where("gs.warehouse_id","in",$org_ids_arr);
            })
            ->when(isset($where['category_ids']),function($query)use($where){
                $category_ids_arr = explode(",",$where['category_ids']);
                $query->where("g.category_ids","in",$category_ids_arr);
            })
            ->group("gs.goods_id")
            ->field("gs.*,sum(gs.stock_number) as sum_stock_number,g.goods_name,g.goods_pprice")
            ->select()->toArray();


        $sum_count = 0;
        $sum_price = 0;
        foreach($list as $k=>$item){
            $pprice = $item['goods_pprice'] ?: 0;
            $number = $item['sum_stock_number'] ?: 0;
            $list[$k]['sum_price'] =  $pprice * $number;
            $sum_count += $number;
            $sum_price += $pprice;
        }

        $result = [
            "list"=>$list,
            "info"=>[
                "sum_count"=>$sum_count,
                "sum_price"=>$sum_price
            ]
        ];
        return $result;
    }

    //根据条件获取库存数量
    public static function getInventoryNumber($where){
        return static::where($where)->sum("stock_number");
    }
}