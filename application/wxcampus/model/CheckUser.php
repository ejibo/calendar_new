<?php


namespace app\wxcampus\model;
use think\Model;

class CheckUser extends Model
{
    /**
     * @purpose：

     * @Author 第09组 沈安强
     * @Date 2019-4-25
     *
     */
    protected $table = 'user_info';
    //检查user_info表内有没有该用户
    public function checkUser($number){
        $CheckUser = new CheckUser();
        $res = $CheckUser->where('work_id ='.$number)->select();
        return $res;
    }

    public function addUser($name,$number){
        $CheckUser = new CheckUser();
        $CheckUser->name = $name;
        $CheckUser->word_id = $number;
        $CheckUser->type_id = 0;
        $CheckUser->depart_id = 0;
        $CheckUser->position_id = 50;
        $CheckUser->is_delete = 0;
        $CheckUser->save();

    }

    //获取对应学号的user_id;
    public function getUserId($number){
        $CheckUser = new CheckUser();
        $res = $CheckUser->where('work_id ='.$number)->column('id');
        return $res[0];
    }
}
