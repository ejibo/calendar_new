<?php
namespace app\wxcampus\controller;

use app\logmanage\model\Log;
use think\Controller;
use app\manageconfig\model\ScheduleDefault as ScheduleDefaultModel;
use think\Exception;
use think\Request;

class ScheduleDefault extends Controller {

    /**管理默认日程的界面*/
    public function index($uid){
        $defaultSchedules=ScheduleDefaultModel::getDefaultSchedules($uid);
        $this->assign("uid",$uid);
        $this->assign("defaultSchedules",$defaultSchedules);
        return $this->fetch();
    }
    /**
    *添加默认日程界面
     */
    public function wx_add_schedule_default($uid){
        $this->assign("uid",$uid);
        $this->assign("title","添加默认日程");
        return $this->fetch();
    }
    /**
     * 添加默认日程动作
     */
    public function addDefaultSchedule($uid){
        $param = Request::instance()->post();
//        try{
//        $res=$this->validate($param,'app\manageconfig\validate\ScheduleDefault');
//        if(!res){
//            return json(['code'=>403,'msg'=>'参数不符合规则']);
//        }
//        }catch(\Exception $e){
//            return json(['code'=>-200,'msg'=>'验证时出错']);
//        }
        $schedule=new ScheduleDefaultModel();
        try{
            $schedule->setUserId($uid);
            $schedule->setTime($param['time']);
            $schedule->setPlace($param['place']);
            $schedule->setItem($param['item']);
            $schedule->setNote($param['note']);
        }catch(\InvalidArgumentException $e) {
            return json(['code'=>$e->getCode(),'msg'=>$e->getMessage()]);
        }
        $schedule->is_delete=0;
        $schedule->update_time=date("Y-m-d H:i:s");
        if($schedule->save()){
            $log= new Log();
            $log->recordLogApi($uid,2,0,"schedule_default",[$schedule->id]);
            return json(['code'=>1,'msg'=>'success']);
        }else{
            return json(['code'=>-1,'msg'=>'添加失败，发生未知错误']);
        }
    }
}
