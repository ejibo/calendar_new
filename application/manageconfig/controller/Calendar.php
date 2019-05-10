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
        $this->assign('defaultSchedules',ScheduleDefault::getDefaultSchedules($username));
        return $this->fetch();
    }
    /**
     * 添加默认事项
     */
    public function addDefaultSchedule()
    {
        $param = Request::instance()->post();
        $this->validate($param,'ScheduleDefault');
        $username = session('username');
        if(empty($username)){
            $username="张三";//测试
        }
        $schedule=new ScheduleDefault();
        try{
            $schedule->setUser($username);
            $schedule->setTime($param['time']);
        }catch(\InvalidArgumentException $e) {
            return json(['code'=>$e->getCode(),'msg'=>$e->getMessage(),'data'=>[]]);
        }
        $schedule->setPlace($param['place']);
        $schedule->setItem($param['item']);
        $schedule->is_delete=0;
        if($schedule->save()){
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
            $place_id=Db::table('schedule_place')->insertGetId(['name'=>$place,'is_delete'=>0]);//如果是之前不存在的地点，则新建一个
        }
        $item_id=Db::table('schedule_item')->where('name',$item)->find()['id'];
        if(empty($item_id)){
            $item_id=Db::table('schedule_item')->insertGetId(['name'=>$item,'is_delete'=>0]);//如果是之前不存在的事项，则新建一个
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
        Db::table('schedule_place')->where('name',$place)->update(['is_delete'=>1]);
        Db::table('schedule_item')->where('name',$item)->update(['is_delete'=>1]);
        $info = Db::name('schedule_default')->where('id', $id)->update(['is_delete'=>1]);
        if($info){
            return $this->success('操作成功', url('index'));
        }else{
            return json(['code'=>-1,'msg'=>'添加失败，发生未知错误','data'=>[]]);
        }
    }
}