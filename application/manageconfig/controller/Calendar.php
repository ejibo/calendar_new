<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:32
 */

namespace app\manageconfig\controller;


use app\common\controller\Common;
use app\common\model\ScheduleDefault;
use think\Db;
use think\Request;

class Calendar extends Common
{
    public function index(){
        $username=session('username');
        $this->assign("username",$username);
        $defaultSchedule=new ScheduleDefault();
        $defaultSchedules=$defaultSchedule->where('username',$username)-> select();
        $this->assign('defaultSchedules',$defaultSchedules);
        return $this->fetch();
    }
    /**
     * 添加默认事项
     */
    public function addDefaultSchedule()
    {
        $param = Request::instance()->post();
        $place = $param['place'];
        $item = $param['item'];
        $time = $param['time'];
        validate($param,'ScheduleDefault');
        $username = session('username');
        $user_id = Db::table("user")->where(["name" => $username, "is_delete" => 0])->find();
        if (is_null($user_id)) {
            return json(["code" => 400, 'msg' => '用户不存在', 'data' => []]);
        }
        $time_id = Db::table('schedule_time')->where('name', $time)->find();
        if (is_null($time_id)) {
            return json(['code' => 1, 'msg' => '未定义的时间段', 'data' => []]);
        }
        $res = Db::table('schedule_default')->where('user_id', $user_id)->where('time_id', $time_id)->where('is_delete', 0)->find();
        if (res != null){
            return json(['code' => 2, 'msg' => '已存在该时间段的默认日程，可点击编辑进行修改', 'data' => []]);//时间必须是之前配置好的时间
        }
        $place_id=Db::table('schedule_place')->where('name',$place)->find();
        if(is_null($place_id)){
            $place_id=Db::table('schedule_place')->insertGetId(['name'=>$place,'is_delete'=>0]);//如果是之前不存在的地点，则新建一个
        }
        $item_id=Db::table('schedule_item')->where('name',$item);
        if(is_null($item_id)){
            $item_id=Db::table('schedule_item')->insertGetId(['name'=>$item,'is_delete'=>0]);//如果是之前不存在的事项，则新建一个
        }
        //开始检查之前是否已经有一样的默认日程了
        $schedule=new ScheduleDefault();
        $schedule->user_id=$user_id;
        $schedule->time_id=$time_id;
        $schedule->place_id=$place_id;
        $schedule->item_id=$item_id;
        $schedule->is_delete=0;
        if($schedule->find(getData())!=null){
            return json(['code'=>3,'msg'=>'已存在相同的记录','data'=>[]]);
        }
        if($schedule->validate(true)->save()){
            return $this->success('操作成功', url('index'));
        }else{
            return json(['code'=>-1,'msg'=>'添加失败，发生未知错误','data'=>[]]);
        }

    }
}