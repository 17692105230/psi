<?php


namespace app\common\model\web;


use app\common\model\BaseModel;

class ClientBase extends BaseModel
{
    protected $table = 'hr_client_base';
    protected $pk = 'client_id';
}