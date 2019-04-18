<?php

namespace app\usermanage\model;
use think\Model;
use think\Db;

class Position extends Model{
	
 /**
  * 第05组 高裕欣
  * 功能：显示列表
  */
	public function getUserPositionList(){
     $list = Db::table('user_position')
                ->select();
     return $list;
 }

    /**
     *第05组 张君兰
     * 功能：修改职位
     */
    public function change($id, $name)
    {
        $position = Position::get($id);//获取用户id
        //更新数据库中的职务名称
        $position->name = $name;
        $position->save();//将更新提交至数据库表
    }
 /**
  * 第05组 高裕欣
  * 功能：作废职位
  */
 function invalid($user_id) {
        $data = array();
        $data['is_delete'] = 1;
        $data['delete_time'] =  Db::raw('now()');
        Db::table('user_position')->where('id', $user_id)->update($data);
    }
	
}

