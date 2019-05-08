<?php
/**
 * Date: 2019/4/20
 * Time: 23:34
 * Author: yang kang
 */

namespace app\querystatistics\controller;
use app\common\controller\Common;

use app\querystatistics\model\ScheduleSearch as SchedulModel;
use think\controller;
use think\Db;
use think\Request;

class Query extends Common{
            
	// public function index()
	// {
	// 	$this->assign();
	// 	return $this->fetch();
    // }

    // public function index(){
        // $schedul_model = new SchedulModel();
        // $info = $schedul_model->searchAllInfo();
        // $this->assign('info',$info);
        // return $this->fetch();
    // }
	
	//George Done
	protected $user_id = 2;

	protected $field_config = array(
		array('name'=>'日期', 'field'=>'date'),
		array('name'=>'时间', 'field'=>'time_id'),
		array('name'=>'事项', 'field'=>'item_id'),
		array('name'=>'地点', 'field'=>'place_id'),
		array('name'=>'备注', 'field'=>'note')
	);

	public function index()
	{
		$this->assign('schedule_info', $this->defaultList());
		$this->assign('fields', $this->field_config);
		return $this->fetch('index_query');
	}
	public function query(Request $request)
	{
		$starttime = $request->param('starttime');
		$endtime = $request->param('endtime');

		$sql = "select * from schedule_info
		 where user_id=".$this->user_id." and date(date) between date('".$starttime."') and date('".$endtime."')";
		$result = Db::query($sql);  
		$len = count($result);
		for($x = 0; $x < $len; $x++){
			$time_id = $result[$x]['time_id'];
			$time = Db::table('schedule_time')->where('id', $time_id)->value('name');
			$result[$x]['time'] = $time;

			$place_id = $result[$x]['place_id'];
			$location = Db::table('schedule_place')->where('id', $place_id)->value('name');
			$result[$x]['location'] = $location;

			$item_id = $result[$x]['item_id'];
			$event = Db::table('schedule_item')->where('id', $item_id)->value('name');
			$result[$x]['event'] = $event;
		}
		$this->assign('schedule_info', $result);
		return $this->fetch('result');
	}
	
	public function defaultList(){
		$sql = "select * from schedule_info where user_id = ".$this->user_id." and is_delete = false";
		$result = Db::query($sql);
		$len = count($result);
		for($x = 0; $x < $len; $x++){
			$time_id = $result[$x]['time_id'];
			$time = Db::table('schedule_time')->where('id', $time_id)->value('name');
			$result[$x]['time'] = $time;

			$place_id = $result[$x]['place_id'];
			$location = Db::table('schedule_place')->where('id', $place_id)->value('name');
			$result[$x]['location'] = $location;

			$item_id = $result[$x]['item_id'];
			$event = Db::table('schedule_item')->where('id', $item_id)->value('name');
			$result[$x]['event'] = $event;
		}
		return $result;
	}
}
