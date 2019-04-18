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

