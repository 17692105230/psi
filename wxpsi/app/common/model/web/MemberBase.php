<?php


namespace app\common\model\web;


use app\common\model\BaseModel;

class MemberBase extends BaseModel
{
    protected $table = "hr_member_base";
    protected $pk = "member_id";

    //关联会员账号
    public function accounts(){
        return $this->hasMany(MemberAccount::class,"member_code", "member_code");
    }
    //获取会员列表
    public function getListData($where){
        $rows = isset($where['rows']) ? $where['rows'] : 10;
        return $this
            ->with(["accounts"])
            ->when(isset($where['com_id']),function($query)use($where){
                return $query->where("com_id","=",$where['com_id']);
            })
            ->when(isset($where['search_data']),function($query)use($where){
                return $query->where(function($q)use($where){
                    return $q->whereOr("member_code","like","%".$where['search_data']."%")
                        ->whereOr("member_name","like","%".$where['search_data']."%")
                        ->whereOr("member_phone","like","%".$where['search_data']."%");
                });
            })
            ->order("member_id","desc")
            ->paginate($rows);
    }

    //修改会员状态
    public function editStatus($where){
        $member = $this->where($where)->find();
        if(!$member){
            return false;
        }
        if($member['member_status']==0){
            $member->member_status = 1;
        }else{
            $member->member_status = 0;
        }
        return $member->save();
    }
}