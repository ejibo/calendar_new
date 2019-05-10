<?php


namespace app\common\model;
use think\Db;
use think\model;

class ScheduleDefault extends Model
{
    public static function getDefaultSchedules($username){
        $user_id = Db::table("user_info")->where(["name" => $username, "is_delete" => 0])->find()['id'];
        $defaultSchedule=model('ScheduleDefault');//new ScheduleDefault();
        $defaultSchedules=$defaultSchedule->where(['user_id'=>$user_id, "is_delete" => 0])-> select();
        return $defaultSchedules;
    }
    public function getTime(){
        return Db::table('schedule_time')->where('id', $this->getData('time_id'))->find()['name'];
    }
    public function getPlace(){
        return Db::table('schedule_place')->where('id', $this->getData('place_id'))->find()['name'];
    }
    public function getItem(){
        return Db::table('schedule_item')->where('id', $this->getData('item_id'))->find()['name'];
    }

    public function setUser($username){
        $user = Db::table("user_info")->where(["name" => $username, "is_delete" => 0])->find();
        if(empty($user)){
            throw new InvalidArgumentException('用户['.$username.']不存在',400);
        }else if ($user['type']==0) {
            throw new InvalidArgumentException('普通用户没有创建默认日程的权限哦~',-403);
        }
        $this->getData()['user_id']=$user['user_id'];
    }
    /**
     * 时间必须是之前配置好的时间，正常来说传过来的时间都是已经存在了的。<br>
     * 并检查之前是否已经有一样的默认日程了
     */
    public function setTime($time){
        $time_id = Db::table('schedule_time')->where(['name'=> $time, "is_delete" => 0])->find('id');
        if (empty($time_id)) {//时间必须是之前配置好的时间，正常来说传过来的时间都是已经存在了的
            throw new InvalidArgumentException('未定义的时间段',404);
        }
        $this->getData()['time_id']=$time_id;
        $this->checkSameTimeDefaultSchedule();
    }
    /**
     * 检查之前是否已经有一样的默认日程了
     */
    public function checkSameTimeDefaultSchedule(){
        $res = Db::table('schedule_default')->where('user_id',$this->getData('user_id'))->
        where('time_id', $this->getData('time_id'))->where('is_delete', 0)->find();
        if ($res != null){
            throw new InvalidArgumentException('已存在该时间段的默认日程，可点击编辑进行修改,创建时间：'.$res['create_time']);
        }
    }
    public function setPlace($place){
        $place_id=Db::table('schedule_place')->where(['name'=>$place,'is_delete'=> 0])->find('id');
        if(empty($place_id)){//如果是之前不存在的地点，则新建一个
            $place_id=Db::table('schedule_place')->insertGetId(['name'=>$place,'is_delete'=>0]);
        }
        $this->getData()['place_id']=$place_id;
    }
    public function setItem($item){
        $item_id=Db::table('schedule_item')->where(['name'=>$item,'is_delete'=> 0])->find('id');
        if(empty($item_id)){//如果是之前不存在的事项，则新建一个
            $item_id=Db::table('schedule_item')->insertGetId(['name'=>$item,'is_delete'=>0]);
        }
        $this->getData()['item_id']=$item_id;
    }
}