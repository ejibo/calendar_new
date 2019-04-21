<?php


namespace app\common\model;
use think\model;

class ScheduleDefault extends Model
{
	public function getTime(){
        return Db::table('schedule_time')->where('id', $this->getData('time_id'))->find()['name'];
    }
    public function getPlace(){
        return Db::table('schedule_place')->where('id', $this->getData('place_id'))->find()['name'];
    }
    public function getItem(){
        return Db::table('schedule_item')->where('id', $this->getData('item_id'))->find()['name'];
    }
}