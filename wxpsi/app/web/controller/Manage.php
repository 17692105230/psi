<?php


namespace app\web\controller;


class Manage extends Controller
{

    public function loadSystemLogGridData()
    {
        $data = [
            'total' => 20,
            'rows' => [
              ['id' => 1,'date' => '2020-12-01 12:01:01', 'operator' => '老王','equipment' => '小程序', 'operation_type' => '添加商品','content' =>'添加商品A','ip' => '192.168.3.1'],
              ['id' => 2,'date' => '2020-12-01 12:01:01', 'operator' => '老王', 'equipment' => '小程序','operation_type' => '添加商品','content' =>'添加商品A','ip' => '192.168.3.1'],
              ['id' => 3,'date' => '2020-12-01 12:01:01', 'operator' => '老王','equipment' => '小程序', 'operation_type' => '添加商品','content' =>'添加商品A','ip' => '192.168.3.1'],
              ['id' => 4,'date' => '2020-12-01 12:01:01', 'operator' => '老王', 'equipment' => '小程序','operation_type' => '添加商品','content' =>'添加商品A','ip' => '192.168.3.1'],
              ['id' => 5,'date' => '2020-12-01 12:01:01', 'operator' => '老王', 'equipment' => '小程序','operation_type' => '添加商品','content' =>'添加商品A','ip' => '192.168.3.1'],
              ['id' => 6,'date' => '2020-12-01 12:01:01', 'operator' => '老王','equipment' => '小程序', 'operation_type' => '添加商品','content' =>'添加商品A','ip' => '192.168.3.1'],
              ['id' => 7,'date' => '2020-12-01 12:01:01', 'operator' => '老王', 'equipment' => '小程序','operation_type' => '添加商品','content' =>'添加商品A','ip' => '192.168.3.1'],
              ['id' => 8,'date' => '2020-12-01 12:01:01', 'operator' => '老王', 'equipment' => '小程序','operation_type' => '添加商品','content' =>'添加商品A','ip' => '192.168.3.1'],
              ['id' => 9,'date' => '2020-12-01 12:01:01', 'operator' => '老王', 'equipment' => '小程序','operation_type' => '添加商品','content' =>'添加商品A','ip' => '192.168.3.1'],
              ['id' => 10,'date' => '2020-12-01 12:01:01', 'operator' => '老王','equipment' => 'PC', 'operation_type' => '添加商品','content' =>'添加商品A','ip' => '192.168.3.1'],
              ['id' => 11,'date' => '2020-12-01 12:01:01', 'operator' => '老王','equipment' => 'PC', 'operation_type' => '添加商品','content' =>'添加商品A','ip' => '192.168.3.1'],
              ['id' => 12,'date' => '2020-12-01 12:01:01', 'operator' => '老王', 'equipment' => 'PC','operation_type' => '添加商品','content' =>'添加商品A','ip' => '192.168.3.1'],
            ],
        ];
        return json($data);
    }
}