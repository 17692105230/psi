<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/15
 * Time: 14:34
 */

namespace app\web\validate;

use think\Validate;

class Sale extends Validate
{
    protected $rule = [
        'client_id' => 'require|number',
        'goods_number' => 'number|number',
        'orders_code' => 'require',
        'lock_version' => 'require|number',

     ];
    protected $message = [
        'client_id' => '系统参数错误',
        'goods_number' => '系统参数错误',
        'lock_version' => '系统参数错误',
        'orders_code' =>'系统参数错误'
    ];
    protected $scene = [
        'update' =>['client_id','goods_number','lock_version'],
        'delete' => ['orders_code']
    ];
}