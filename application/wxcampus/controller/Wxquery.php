<?php
/*
 * 查询用户日程
 * 第二组小刘
 * 2019-5-15
 */

namespace app\wxcampus\controller;
use app\common\controller\Common;
use think\Controller;
use think\Db;
use think\Request;
use app\wxcampus\model\Query as query;


class Wxquery extends controller
{
     public function Index()
    {
    	//按照部门、职务、姓名 查询用户日程
        $depart = 0;
        $pos = 0;
        $name = '';
        $work_id = 0;
        if(input('?get.depart') && input('?get.pos') && input('?get.name') && input('?get.work_id')){
            $depart = Request::instance()->param('depart');
            $pos = Request::instance()->param('pos');
            $name = Request::instance()->param('name','','strip_tags,htmlspecialchars');
            $work_id = Request::instance()->param('work_id','','strip_tags,htmlspecialchars');
        }
    	
        $allInfo = array();
        $allInfo = Db::table('schedule_info')
            // ->alias(['schedule_info' => 'a', 'user_info' => 'b', 'user_position' => 'c', 'schedule_time' => 'd', 'schedule_place' => 'e', 'schedule_item' => 'f'])
            ->join('user_info', 'schedule_info.user_id = user_info.id')
            ->join('user_depart', 'user_info.depart_id = user_depart.id')
            ->join('user_position', 'user_info.position_id = user_position.id')
            ->join('schedule_time', 'schedule_info.time_id = schedule_time.id')
            ->join('schedule_place', 'schedule_info.place_id = schedule_place.id')
            ->join('schedule_item', 'schedule_info.item_id = schedule_item.id')
            ->where('schedule_info.is_delete', 0)
            ->where('user_info.is_delete', 0)
            ->where('user_info.depart_id', $depart)
            ->where('user_info.position_id', $pos)
            ->where('user_info.name', $name)
            ->where('user_info.work_id', $work_id)
            ->where('schedule_info.date', '>= time', date('Y-m-d', time()))
            ->field('schedule_time.name as time, schedule_place.name as place, schedule_item.name as item')
            ->select();

            dump($allInfo);

      	if (!is_array($allInfo)){
            echo '检索结果无';
        }else{
            $this->assign('result', $allInfo);
        }
      



        $depart_list = array();
        $depart_list = Db::table('user_depart')
          ->where('is_delete', 0)
          ->select();
      
        $position_list = array();
        $position_list = Db::table('user_position')
          ->where('is_delete', 0)
          ->select();
      
        $this->assign('depart', $depart_list);
      	$this->assign('pos', $position_list);
        


        return $this->fetch('index/wx_search');
    }
  
}
