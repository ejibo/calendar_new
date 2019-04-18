<?php
/**
 * Date: 2019/4/18
 * Time: 0:34
 * Author: liu
 */

namespace app\querystatistics\controller;

use think\controller;
use think\Db;
use think\Request;

class Query extends controller{
            
	// public function index()
	// {
	// 	$this->assign();
	// 	return $this->fetch();
    // }
    
    public function Query($keyword){
        $list = DB::query("SELECT user_info.name name,schedule_info.date date,schedule_time.name time,schedule_place.name place,schedule_item.name item 
                                            FROM schedule_info,schedule_item,schedule_place,schedule_time,user_info 
                                            where schedule_info.time_id=schedule_time.id and 
                                            schedule_info.item_id=schedule_item.id 
                                            and schedule_info.place_id=schedule_place.id and 
                                            schedule_info.user_id=user_info.id and 
                                            schedule_info.is_delete=0 and 
                                            schedule_item.is_delete=0 and 
                                            schedule_place.is_delete=0 and 
                                            schedule_time.is_delete=0 and 
                                            user_info.name=".$keyword);
        $this->assign('arealist', $list);
        return $this->fetch('info');
    }
}
?>