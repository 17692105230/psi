<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2020/12/18
 * Time: 9:38
 */

namespace app\web\controller;


class Customer extends Controller
{
    public function loadCustomerClassify(){
        $data = [
            'total' => 100,
            'rows' => [
                ['customer_classify_id' => 1, 'customer_classify_name' => '重点客户','customer_classify_price' => '200.00', 'customer_classify_describe' => ''],
                ['customer_classify_id' => 2, 'customer_classify_name' => '普通客户','customer_classify_price' => '2000.00', 'customer_classify_describe' => ''],
                ['customer_classify_id' => 3, 'customer_classify_name' => '小客户','customer_classify_price' => '300000.00', 'customer_classify_describe' => ''],
                ['customer_classify_id' => 4, 'customer_classify_name' => '默认客户','customer_classify_price' => '10000.23', 'customer_classify_describe' => '默认客户'],
                ['customer_classify_id' => 5, 'customer_classify_name' => '新客户（销货宝）','customer_classify_price' => '1568.24', 'customer_classify_describe' => ''],
                ['customer_classify_id' => 6, 'customer_classify_name' => '老客户（进销存）','customer_classify_price' => '2000000.00', 'customer_classify_describe' => '有钱人'],
                ['customer_classify_id' => 7, 'customer_classify_name' => '东城客户（进销存）','customer_classify_price' => '3000000.00', 'customer_classify_describe' => '有钱人'],
            ],
        ];
        return json($data);
    }
}