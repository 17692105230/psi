<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/26
 * Time: 17:43
 */

namespace app\common\model\web;


use app\common\model\BaseModel;

class ClientAccount extends BaseModel
{
    protected $table = 'hr_client_account';
    protected $pk = 'account_id';
}