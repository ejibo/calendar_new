<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:32
 */

namespace app\manageconfig\controller;


use app\common\controller\Common;
use app\logmanage\model\Log;
use app\manageconfig\model\ScheduleDefault;
use app\manageconfig\model\ScheduleTime as ScheduleTime;
use think\Db;
use think\Request;
use think\Session;

class Calendar extends Common
{
    public function index(){
        $this->assign('defaultSchedules',ScheduleDefault::getDefaultSchedules());
        return $this->fetch();
    }
    public function search(){
        $user=Request::instance()->get('user');
        $user_id=Db::table("user_info")->where(['name'=>$user,'is_delete'=>0])->value('id');
        if(empty($user_id))
            return json(['code'=>404,'msg'=>'用户不存在','data'=>[]]);
        $this->assign('uid',$user_id);
        $this->assign('uname',$user);
        $this->assign('defaultSchedules',ScheduleDefault::getDefaultSchedules($user));
        return $this->fetch();
    }
    /**
     * 添加默认事项
     */
    public function addDefaultSchedule()
    {
        $param = Request::instance()->post();
        $this->validate($param,'ScheduleDefault');
        $schedule=new ScheduleDefault();
        try{
            $schedule->setUserId($param['uid']);
            $schedule->setDay($param['day']);
            $schedule->setTime($param['time']);
            $schedule->checkSameTimeDefaultSchedule();
            $schedule->setPlace($param['place']);
            $schedule->setItem($param['item']);
        }catch(\InvalidArgumentException $e) {
            return json(['code'=>$e->getCode(),'msg'=>$e->getMessage(),'data'=>[]]);
        }
        $schedule->is_delete=0;
        $schedule->update_time=date("Y-m-d H:i:s");
        if($schedule->save()){
            $log= new Log();
            $log->recordLogApi(ADMIN_ID,2,1,"schedule_default",[$schedule->id]);
            return json(['code'=>1,'msg'=>'success','data'=>[]]);
        }else{
            return json(['code'=>-1,'msg'=>'添加失败，发生未知错误','data'=>[]]);
        }
    }
  
  	/**
      *修改默认地点、事项表
    */
  	public function editDefaultSchedule()
    {
        $param = Request::instance()->post();
        $user_id = Session::get('admin_id');
        $place = trim($param['place']);
        $item = trim($param['item']);
        $id = trim($param['id']);

        //修改默认地点。如果是之前不存在的地点，则新建
        $place_id=Db::table('schedule_place')->where('name',$place)->find()['id'];
        if(empty($place_id)){
            $place_id=Db::table('schedule_place')->insertGetId(['name'=>$place,'is_delete'=>1]);//如果是之前不存在的地点，则新建一个
        }

        //修改默认事项。如果是之前不存在的事项，则新建
        $item_id=Db::table('schedule_item')->where('name',$item)->find()['id'];
        if(empty($item_id)){
            $item_id=Db::table('schedule_item')->insertGetId(['name'=>$item,'is_delete'=>1]);//如果是之前不存在的事项，则新建一个
        }

        $info = Db::name('schedule_default')->where('id', $id)->update(['user_id'=>$user_id, 'place_id'=>$place_id, 'item_id'=>$item_id]);
        if($info){
            return $this->success('操作成功', url('index'));
        }else{
            return json(['code'=>-1,'msg'=>'添加失败，发生未知错误','data'=>[]]);
        }

    }	




    /**
     *删除默认的缺省日程
     */
    public function deleteDefaultSchedule()
    {
        $param = Request::instance()->post();
        $id = trim($param['id']);
        $username = session('username');
        $this->validate($param, 'ScheduleDefault');


        $place_id = Db::table("schedule_default")->where(["id" => $id,])->find()['place_id'];
        $item_id = Db::table("schedule_default")->where(["id" => $id,])->find()['item_id'];
        $time_id = Db::table("schedule_default")->where(["id" => $id,])->find()['time_id'];
        Db::table('schedule_place')->where('id', $place_id)->update(['is_delete' => 1, "delete_time" => date("Y-m-d H:i:s")]);
        Db::table('schedule_item')->where('id', $item_id)->update(['is_delete' => 1, "delete_time" => date("Y-m-d H:i:s")]);
        Db::table('schedule_time')->where('id', $time_id)->update(['is_delete' => 1, "delete_time" => date("Y-m-d H:i:s")]);
        $info = Db::name('schedule_default')->where('id', $id)->update(['is_delete' => 1, "delete_time" => date("Y-m-d H:i:s")]);
        if($info){
            return $this->success('操作成功', url('index'));
        }else{
            return json(['code'=>-1,'msg'=>'删除失败，发生未知错误','data'=>[]]);
        }

    }
}