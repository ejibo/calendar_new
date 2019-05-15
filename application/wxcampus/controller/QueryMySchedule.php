<?php
namespace app\wxcampus\controller;

use think\Controller;
use think\Db;


class QueryMySchedule extends Controller
{
	$Index = controller('Index');
	public $stu_number;
	public $user_id;

	protected getUserId($stu_number){
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

	public function defaultList(){
		$this->stu_number = getStuNumber();
		echo "".$this->stu_number;
		$this->user_id = getUserId($this->stu_number);
		$sql = "select * from schedule_info where user_id = ".$this->user_id." and is_delete = false";
		$result = Db::query($sql);
		return $result;
	}

	public function index()
	{
		$this->assign('schedule_info', $this->defaultList());
		$this->assign('fields', $this->field_config);
		return $this->fetch('index');
	}

	public function getMyScheduleInfo()
	{
		$Index = controller('Index'); //同一个controller下的控制器可以直接调用
		$user_number = $Index->getUserNumber();

		$res = Db::table('user_info')->where('work_id', $user_number)->find();
		if($res){
			if($res['type_id'] == 0){
				echo "Sorry, 没有你的日程信息"；
				return;
			}
			else{
				$name = $res[]
			}
		}
		else{
			echo "没有你的信息"；
		}
	}
}



?>