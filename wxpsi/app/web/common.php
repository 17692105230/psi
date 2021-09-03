<?php

// 这是系统自动生成的公共文件
/**
 * 生成treeGrade数据格式
 * @param array $arr 原始数据
 * @param string $pk 主键字段名称
 * @param string $pid 父级字段名称
 * @param string $child 子级节点名称
 * @return array
 */
function createTree($arr = array(), $pk = 'id', $upid = 'pid', $child = 'children')
{
    $items = array();
    foreach ($arr as $val) {
        $items[$val[$pk]] = $val;
    }
    $tree = array();
    foreach ($items as $k => $val) {
        if (isset($items[$val[$upid]])) {
            $items[$val[$upid]][$child][] =& $items[$k];
        } else {
            $tree[] = &$items[$k];
        }
    }
    return $tree;
}

function pageOffset($arr, $page = 'page', $rows = 'rows', $default = 20)
{
    $c_page = isset($arr[$page]) ? $arr[$page] : 1;
    $c_rows = isset($arr[$rows]) ? $arr[$rows] : $default;
    $offset = ($c_page - 1) * $c_rows;
    return [$offset, $c_rows];
}
// 判断变量是否为空,如果是空字符串,null,0 返回真
function isEmpty($value){
    if(!isset($value)){
        return true;
    }
    if(empty($value)){
        return true;
    }
    if(is_null($value)){
        return true;
    }
    return false;
}

/**
 * 根据仓库和商品id查出库存数量
 * zll
 * Date: 2021/6/11
 * Time: 18:19
 * @param $list goodsstock表的数据集
 * @return array
 */
function getStockGoodsColorSize($list){
    $goods_data = [];
    foreach ($list as $v){
        //判断是否存在此商品
        if(isset($goods_data[$v['goods_id']])){
            $goods_data[$v['goods_id']] += $v["stock_number"];
        }else{
            $goods_data[$v['goods_id']] = $v["stock_number"];
        }
        //判断是否存在此商品-颜色
        if(isset($goods_data[$v['goods_id']."-".$v['color_id']])){
            $goods_data[$v['goods_id']."-".$v['color_id']] += $v["stock_number"];
        }else{
            $goods_data[$v['goods_id']."-".$v['color_id']] = $v["stock_number"];
        }
        //判断是否存在此商品-颜色-尺寸
        if(isset($goods_data[$v['goods_id']."-".$v['color_id']."-".$v['size_id']])){
            $goods_data[$v['goods_id']."-".$v['color_id']."-".$v['size_id']] += $v["stock_number"];
        }else{
            $goods_data[$v['goods_id']."-".$v['color_id']."-".$v['size_id']] = $v["stock_number"];
        }
    }

    return $goods_data;
}

