<?php
namespace app\wxcampus\controller;

use think\Controller;
use think\Db;
use think\Request;


class QueryMySchedule extends Controller
{
	public $stu_number;
	public $user_id;

	protected function getUserId($stu_number){
		$result = Db::table("user_info")->where('work_id', $stu_number)->find();
		return $result['id'];
	}

	protected $field_config = array(
		array('name'=>'日期', 'field'=>'date', 'icon'=>'fa-pencil-square-o'),
		array('name'=>'时间', 'field'=>'time_id', 'icon'=>'fa-check-square-o'),
		array('name'=>'事项', 'field'=>'item_id', 'icon'=>'fa-check-square-o'),
		array('name'=>'地点', 'field'=>'place_id', 'icon'=>'fa-check-square-o'),
		array('name'=>'备注', 'field'=>'note', 'icon'=>'fa-pencil-square-o')
	);

	public function defaultList($number){
		$this->user_id = $this->getUserId($number);
		//echo $this->user_id;
		$sql = "select * from schedule_info where user_id = ".$this->user_id;
		$result = Db::query($sql);
		dump($result);
		//return $result;
	}

	public function index()
	{
		$number = Request::instance()->param('number');
		$this->assign('schedule_info', $this->defaultList($number));
		$this->assign('fields', $this->field_config);
		return $this->fetch('result');
	}

	public function getMyScheduleInfo()
	{
		$model = new SIndex(); //同一个controller下的控制器可以直接调用
		$user_number = $model->getUserNumber();

		$res = Db::table('user_info')->where('work_id', $user_number)->find();
		if($res){
			if($res['type_id'] == '0'){
				echo "Sorry, 没有你的日程信息";
			}
			else{
				echo $res['type_id'];
			}
		}
		else{
			echo "没有你的信息";
		}
	}
}



?>