<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:32
 */

namespace app\manageconfig\controller;


use app\common\controller\Common;
use app\manageconfig\model\ScheduleDefault;
use think\Db;
use think\Request;

class Calendar extends Common
{
    public function index(){
        $username=session('username');
        if(empty($username)){
            $username="张三";//测试
        }
        $this->assign("username",$username);
        $user_id = Db::table("user_info")->where(["name" => $username, "is_delete" => 0])->find()['id'];
        $defaultSchedule=model('ScheduleDefault');//new ScheduleDefault();
        $defaultSchedules=$defaultSchedule->where('user_id',$user_id)-> select();
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
        $this->validate($param,'ScheduleDefault');
        $username = session('username');
        if(empty($username)){
            $username="张三";//测试
        }
        $user_id = Db::table("user_info")->where(["name" => $username, "is_delete" => 0])->find()['id'];
        if (empty($user_id)) {
            return json(["code" => 400, 'msg' => '用户['.$username.']不存在', 'data' => []]);
        }
        $time_id = Db::table('schedule_time')->where('name', $time)->find()['id'];
        if (empty($time_id)) {
            return json(['code' => 1, 'msg' => '未定义的时间段', 'data' => []]);
        }
        $res = Db::table('schedule_default')->where('user_id', $user_id)->where('time_id', $time_id)->where('is_delete', 0)->find();
        if ($res != null){
            return json(['code' => 2, 'msg' => '已存在该时间段的默认日程，可点击编辑进行修改', 'data' => []]);//时间必须是之前配置好的时间
        }
        $place_id=Db::table('schedule_place')->where('name',$place)->find()['id'];
        if(empty($place_id)){
            $place_id=Db::table('schedule_place')->insertGetId(['name'=>$place,'is_delete'=>0]);//如果是之前不存在的地点，则新建一个
        }
        $item_id=Db::table('schedule_item')->where('name',$item)->find()['id'];
        if(empty($item_id)){
            $item_id=Db::table('schedule_item')->insertGetId(['name'=>$item,'is_delete'=>0]);//如果是之前不存在的事项，则新建一个
        }
        //开始检查之前是否已经有一样的默认日程了
        $schedule=model('ScheduleDefault');
        $schedule->user_id=$user_id;
        $schedule->time_id=$time_id;
        $schedule->place_id=$place_id;
        $schedule->item_id=$item_id;
        $schedule->is_delete=0;
        if($schedule->find($schedule->getData())!=null){
            return json(['code'=>3,'msg'=>'已存在相同的记录','data'=>[]]);
        }
        if($schedule->save()){
            return $this->success('操作成功', url('index'));
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
        $id = trim($param['id']);
        $place = trim($param['place']);
        $item = trim($param['item']);
        $username = session('username');
        $this->validate($param,'ScheduleDefault');

        if(empty($username)) {
            $username = "张三";//测试
        }
        $user_id = Db::table("user_info")->where(["name" => $username, "is_delete" => 0])->find()['id'];
        if (empty($user_id)) {
            return json(["code" => 400, 'msg' => '用户['.$username.']不存在', 'data' => []]);
        }
        $place_id=Db::table('schedule_place')->where('name',$place)->find()['id'];
        if(empty($place_id)){
            $place_id=Db::table('schedule_place')->insertGetId(['name'=>$place,'is_delete'=>1]);//如果是之前不存在的地点，则新建一个
        }
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


    public function deleteDefaultSchedule()
    {

        $param = Request::instance()->post();
        $id = trim($param['id']);
        $place = trim($param['place']);
        $item = trim($param['item']);
        $username = session('username');
        $this->validate($param,'ScheduleDefault');

        if(empty($username)) {
            $username = "张三";//测试
        }
        $user_id = Db::table("user_info")->where(["name" => $username, "is_delete" => 0])->find()['id'];
        if (empty($user_id)) {
            return json(["code" => 400, 'msg' => '用户['.$username.']不存在', 'data' => []]);
        }
        $place_id=Db::table('schedule_place')->where('name',$place)->update(['is_delete'=>1])->find()['id'];

        $item_id=Db::table('schedule_item')->where('name',$item)->update(['is_delete'=>1])->find()['id'];


        $info = Db::name('schedule_default')->where('id', $id)->update(['is_delete'=>1]);
        if($info){
            return $this->success('操作成功', url('index'));
        }else{
            return json(['code'=>-1,'msg'=>'添加失败，发生未知错误','data'=>[]]);
        }
    }
}