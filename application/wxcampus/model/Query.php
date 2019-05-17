<?php
namespace app\query\model;

use think\Model;
use think\Db;
use think\Request;


class Query extends Model
{
    public function wx_query()
    {        
        $query_name = Request::instance()->param('query_name');
        $work_id = Request::instance()->param('query_id');

        // $date1 = new DateTime("now");
                            
        $test = Db::table('schedule_info')
            //->alias(['schedule_info' => 'a', 'user_info' => 'b', 'user_position' => 'c', 'schedule_time' => 'd', 'schedule_place' => 'e', 'schedule_item' => 'f'])
            ->join('user_info', 'schedule_info.user_id = user_info.id')
            ->join('user_position', 'user_info.position_id = user_position.id')
            ->join('schedule_time', 'schedule_info.time_id = schedule_time.id')
            ->join('schedule_place', 'schedule_info.place_id = schedule_place.id')
            ->join('schedule_item', 'schedule_info.item_id = schedule_item.id')
            ->where('schedule_item.is_delete', 0)
            ->where('user_info.name', $query_name)
            ->where('user_info.work_id', $work_id)
            //->where(date_diff('2018-09-09', '2018-10-09') > 0)
            ->field('user_info.name as name, schedule_time.name as time, schedule_place.name as place, schedule_item.name as item, user_info.id as id')
            ->select();
        return $test;
    }
}