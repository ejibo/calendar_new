<?php


namespace app\manageconfig\model;
use think\Db;
use think\Model;

class ScheduleDefault extends Model
{
    public static function getDefaultSchedules($user_id){
        $defaultSchedule=new ScheduleDefault();
        $defaultSchedules=$defaultSchedule->where(['user_id'=>$user_id, "is_delete" => 0])-> select();
        return $defaultSchedules;
    }

    public function getTime(){
        return Db::table('schedule_time')->where('id', $this->getData('time_id'))->value('name');
    }

    public function getPlace(){
        return Db::table('schedule_place')->where('id', $this->getData('place_id'))->value('name');
    }

    public function getItem(){
        return Db::table('schedule_item')->where('id', $this->getData('item_id'))->value('name');
    }

    public function setUserId($user_id){
        $this->data('user_id',$user_id);
    }

    /**
     * 会检查之前是否已经有一样的默认日程了
     * @throws \InvalidArgumentException 存在相同时间段的话或者是未定义的时间段的话抛出
     */
    public function setTime($time){
        $time_id = Db::table('schedule_time')->where('name', $time)->value('id');
        if (empty($time_id)) {
            $time_id=Db::table('schedule_time')->insertGetId(['name'=>$time,'is_delete'=>0]);
        }
        $this->data('time_id',$time_id);
        $this->checkSameTimeDefaultSchedule();
    }

    /**
     * 检查之前是否已经有一样的默认日程了
     * @throws \InvalidArgumentException 存在相同时间段的话抛出
     */
    public function checkSameTimeDefaultSchedule(){
        $res = Db::table('schedule_default')->where('user_id',$this->getData('user_id'))->
        where('time_id', $this->getData('time_id'))->where('is_delete', 0)->find();
        if ($res != null){
            throw new \InvalidArgumentException('已存在该时间段的默认日程，可点击编辑进行修改,创建时间：'.$res['create_time']);
        }
    }

    public function setPlace($place){
        $place_id=Db::table('schedule_place')->where(['name'=>$place,'is_delete'=> 0])->value('id');
        if(empty($place_id)){//如果是之前不存在的地点，则新建一个
            $place_id=Db::table('schedule_place')->insertGetId(['name'=>$place,'is_delete'=>0]);
        }
        $this->data('place_id',$place_id);
    }

    public function setItem($item){
        $item_id=Db::table('schedule_item')->where(['name'=>$item,'is_delete'=> 0])->value('id');
        if(empty($item_id)){//如果是之前不存在的事项，则新建一个
            $item_id=Db::table('schedule_item')->insertGetId(['name'=>$item,'is_delete'=>0]);
        }
        $this->data('item_id',$item_id);
    }
    public function setNote($note){
        $this->data('note',$note);
    }
}