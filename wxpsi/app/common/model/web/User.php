<?php


namespace app\common\model\web;


use app\common\model\BaseModel;

class User extends BaseModel
{
    protected $table = 'hr_user';
    protected $pk = 'user_id';
}