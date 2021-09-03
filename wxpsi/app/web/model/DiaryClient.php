<?php


namespace app\web\model;

use app\common\model\web\DiaryClient as DiaryClientModel;

class DiaryClient extends DiaryClientModel
{
    public function addRecord($data){
        return $this->insert([
            'client_id' => $data['client_id'],
            'orders_code' => $data['orders_code'],
            'user_id' => $data['user_id'],
            'settlement_id' => $data['settlement_id'],
            'account_id' => $data['account_id'],
            'pmoney' => $data['pmoney'],
            'rmoney' => $data['rmoney'],
            'pbalance' => $data['pbalance'],
//            'settlement_balance' => $data['settlement_balance'],
            'client_balance' => $data['client_balance'],
            'create_time' => $data['create_time'],
            // 'item_type' => $data['item_type'],
            'remark' => $data['remark'],
            'com_id' => $data['com_id']
        ]);
    }
}