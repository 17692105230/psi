<?php


namespace app\web\validate;

use think\Validate;

class Material extends Validate
{
   protected $rule = [
     'lock_version' => 'require',
     'dict_id' => 'require',
   ];

   protected $message = [
     'lock_version.require' => '版本信息不能为空',
     'dict_id.require' => 'id不能为空',
   ];

   protected $scene = [
     'edit' => ['dict_id'],
   ];
}