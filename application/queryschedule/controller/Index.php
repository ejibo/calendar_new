<?php
namespace app\querySchedule\controller;
use app\common\controller\Common as Common;
use think\Request;
use think\Db; //引入数据库

class Index extends Common
{
	public function index()
	{
		return $this->fetch();
	}
	public function query(Request $request)
	{
		$starttime = $request->param('starttime');
		$endtime = $request->param('endtime');
		//$starttime = '2019-04-10';
		//$endtime = '2019-05-01';
		$sql = "select * from schedule_info where date(date) between date('".$starttime."') and date('".$endtime."')";
		$result = Db::query($sql);  
		$len = count($result);
		for($x = 0; $x < $len; $x++){
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
		$list = DB::name('schedule_info')->select();
		return $list;
	}
}