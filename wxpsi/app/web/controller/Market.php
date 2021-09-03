<?php
/**
 * 销售订单
 * User: ccl
 * Date: 2020/12/18
 * Time: 11:34
 */

namespace app\web\controller;


class Market extends Controller
{
    public function loadClient(){
        $data = [
                ['id' => 1, 'name' => '张三'],
                ['id' => 2, 'name' => '李四'],
                ['id' => 3, 'name' => '王五'],
                ['id' => 4, 'name' => '赵六'],
                ['id' => 5, 'name' => '周日'],
        ];
        return json($data);
    }
    public function loadApplyClient(){
        return json(json_decode('{"total":2,"rows":[{"orders_id":2,"orders_code":"SP180401093802243","orders_date":1525449600,"client_id":3,"client_name":"李四五","orders_status":9},{"orders_id":1,"orders_code":"SP180309234651672","orders_date":0,"client_id":2,"client_name":"天下靶场","orders_status":9}],"status":null}'));

    }
    public function getGoodsData()
    {
        $data = [
            ['goods_id' => 1, 'goods_name' => '801秋冬男士加厚裤子'],
            ['goods_id' => 2, 'goods_name' => 'K1 2L可乐'],
            ['goods_id' => 3, 'goods_name' => '秋冬女士修身外套'],
            ['goods_id' => 4, 'goods_name' => '805秋冬女士修身裤子'],
        ];

        return json($data);
    }
    public function getGoodsColorData()
    {
        return json(json_decode('[{"color_id":2,"color_name":"红色"},{"color_id":3,"color_name":"蓝色"},{"color_id":4,"color_name":"黑色"},{"color_id":10,"color_name":"花白色"},{"color_id":6,"color_name":"中咖色"},{"color_id":7,"color_name":"浅灰色"},{"color_id":8,"color_name":"深灰色"},{"color_id":15,"color_name":"美女"}]'));
    }

    public function getGoodsSizeData()
    {

        return json(json_decode('[{"size_id":3,"size_name":"S"},{"size_id":4,"size_name":"M"},{"size_id":5,"size_name":"L"},{"size_id":6,"size_name":"XL"},{"size_id":7,"size_name":"2XL"},{"size_id":8,"size_name":"3XL"},{"size_id":10,"size_name":"31码"},{"size_id":11,"size_name":"32码"},{"size_id":12,"size_name":"33码"},{"size_id":13,"size_name":"34码"},{"size_id":14,"size_name":"35码"},{"size_id":15,"size_name":"36码"},{"size_id":16,"size_name":"37码"}]'));
    }
}
