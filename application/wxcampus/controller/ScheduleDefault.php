<?php
namespace app\wxcampus\controller;

use app\logmanage\model\Log;
use think\Controller;
use app\manageconfig\model\ScheduleDefault as ScheduleDefaultModel;
use think\Request;

class ScheduleDefault extends Controller {
    private $user_id;
    public function index($user_id){
        $this->user_id=$user_id;
        $defaultSchedules=ScheduleDefault::getDefaultSchedules($user_id);
        $this->assign("defaultSchedules",$defaultSchedules);
        return $this->fetch();
    }
    public function wx_add_schedule_default($user_id){
        $this->assign("user_id",$user_id);
        $this->assign("title","添加默认日程");
        return $this->fetch();
    }
    /**
     * 添加默认事项
     */
    public function addDefaultSchedule($user_id){
        $param = Request::instance()->post();
        $schedule=new ScheduleDefault();
        try{
            $schedule->setUserId($user_id);
            $schedule->setTime($param['time']);
            $schedule->setPlace($param['place']);
            $schedule->setItem($param['item']);
        }catch(\InvalidArgumentException $e) {
            return json(['code'=>$e->getCode(),'msg'=>$e->getMessage()]);
        }
        $schedule->is_delete=0;
        $schedule->update_time=date("Y-m-d H:i:s");
        if($schedule->save()){
            $log= new Log();
            $log->recordLogApi($this->user_id,2,0,"schedule_default",[$schedule->id]);
            return json(['code'=>1,'msg'=>'success']);
        }else{
            return json(['code'=>-1,'msg'=>'添加失败，发生未知错误']);
        }
    }
}
