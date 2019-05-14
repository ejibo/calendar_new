<?php

namespace app\wxcampus\controller;

use think\Controller;
use app\wx\controller\Personal;
class WxCalendar extends Personal
{
    public function Index(){
        $this->assign('date', date('Y-m-d'));
        $this->assign('cells', $this->getOneDaySchedule(strtotime('2019-04-09')));
        return $this->fetch("index/wx_calendar");
    }
    public function add(){
        $this->assign('schedule_item', $this->getScheduleItem());
        $this->assign('schedule_place', )
        return $this->fetch("index/wx_add");
    }
}